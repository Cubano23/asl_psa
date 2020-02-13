<?php require_once("bean/beanparser/htmltags.php"); ?>
<?php require_once("view/jsgenerator/jsgenerator.php"); ?>
<?php require_once("view/common/vars.php"); ?>
<?php require_once("tools/date.php"); ?>
<?php require_once("tools/arrays.php"); ?>
<?php
require_once ("Config.php");
$config = new Config();
?>

<?php global $account; ?>
<?php global $rowsList; ?>
<?php global $param; ?>
<?php global $currentObjectName; ?>
<?php global $currentObjectClass; ?>
<?php global $signature; ?>

<?php
$row_minimal = array();
for($i=0;$i<count($rowsList);$i++) {
    if($rowsList[$i]["date"] > date('Y-m-d', strtotime('now -6 months'))) {
        array_push($row_minimal, $rowsList[$i]);
    }
}
?>

<table width="30%">
    <tr>
        <td>Tri par :</td>
        <td>
            <select id="orderby">
                <option value="date">Date de dernière évaluation</option>
                <option value="dossier">Dossier</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Dossier :</td>
        <td><input type="text" id="fitlerDossier"></td>
    </tr>
</table>

<p>&nbsp;</p>

<div id="results">
    <!-- <table width="99%"  border="1" cellspacing="0" cellpadding="0">
    <CAPTION><?php echo(count($row_minimal)) ?> enregistrements trouvés dans les 6 derniers mois</CAPTION>
    <tr>
      <th scope="col">&nbsp;Dernière évaluation</th>
      <th scope="col">&nbsp;Dossier</th>
      <th scope="col">&nbsp;Sexe</th>
      <th scope="col">&nbsp;Date de naissance</th>
      <th scope="col">&nbsp;Date de consentement</th>
      <th scope="col">&nbsp;Consulter</th>
    </tr>
    <?php for($i=0;$i<count($row_minimal);$i++): ?>
      <tr>
        <td>&nbsp;<?php echo date('d/m/Y', strtotime($row_minimal[$i]["date"])) ?></td>
        <td>&nbsp;<?php echo(getDoubleArrayElement($row_minimal,$i,"numero")); ?></td>
        <td>&nbsp;<?php echo($sexe[getDoubleArrayElement($row_minimal,$i,"sexe")]); ?></td>
        <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($row_minimal,$i,"dnaiss"))); ?></td>
        <td>&nbsp;<?php echo(mysqlDateTodate(getDoubleArrayElement($row_minimal,$i,"dconsentement"))); ?></td>
        <td>&nbsp;<?php
        $additionalParams = array("Dossier:dossier:numero"=>getDoubleArrayElement($row_minimal,$i,"numero"));
        buildLink("","Consulter","$path/controler/ActionControler.php",$param->controler,ACTION_LIST,array(PARAM_LIST_BY_DOSSIER),$additionalParams);
        ?>
        </td>
      </tr>
    <?php endfor ?>
    <tr>
      <td colspan="6" align="center"><br /><a style="cursor:pointer" id="view_all">VOIR TOUTES LES EVALUATIONS</a><br /></td>
    </tr>
  </table> -->
</div>



<script type="text/javascript">
    var limit_resume = "<?php echo date('Y-m-d', strtotime('now -6 months')) ?>";
    var _list_mode = "resume";
    var _list_order = "date";
    var _list_search = "";

    var _consults = [];
    <?php for($i=0;$i<count($rowsList);$i++): ?>
    var temp = {
        date: "<?php echo $rowsList[$i]['date'] ?>",
        date_fr: "<?php echo date('d/m/Y', strtotime($rowsList[$i]['date'])) ?>",
        dossier: "<?php echo getDoubleArrayElement($rowsList,$i,'numero') ?>",
        sexe: "<?php echo $sexe[getDoubleArrayElement($rowsList,$i,'sexe')] ?>",
        dnaiss: "<?php echo mysqlDateTodate(getDoubleArrayElement($rowsList,$i,'dnaiss')) ?>",
        dconsentement: "<?php echo mysqlDateTodate(getDoubleArrayElement($rowsList,$i,'dconsentement')) ?>",
        link: "<?= $config->psa_path ?>/controler/ActionControler.php?controlerparams:param:controler=EvaluationInfirmierControler&controlerparams:param:action=AL&controlerparams:param:param1=PLISDOSS&Dossier:dossier:numero=<?php echo getDoubleArrayElement($rowsList,$i,'numero') ?>"
    }
    _consults.push(temp)
    <?php endfor ?>

    $(document).on('ready', function() {

        var current_list = calculListe();
        constructTable(current_list);
        viewBtnResume()

        function calculListe() {
            var aTemp = [];

            // search
            if(_list_search != '') {
                for(var i in _consults) {
                    if(_consults[i].dossier.substring(0, _list_search.length) == _list_search) {
                        aTemp.push(_consults[i]);
                    }
                }
            }
            else {
                aTemp = _consults;
            }

            // limit
            if(_list_mode == "full") {
                aTemp = calculListeFull(aTemp)
            }
            else {
                aTemp = calculListeResume(aTemp)
            }
            // order
            if(_list_order == "date") {
                aTemp.sort(compareDate);
            }
            else {
                aTemp.sort(compareDossier);
            }
            return aTemp;
        }
        function compareDate(a,b) {
            if (a.date > b.date)
                return -1;
            if (a.date < b.date)
                return 1;
            return 0;
        }
        function compareDossier(a,b) {
            if (a.dossier < b.dossier)
                return -1;
            if (a.dossier > b.dossier)
                return 1;
            return 0;
        }
        function calculListeResume(list) {
            var aTemp = [];
            for(var i in list) {
                if(list[i].date > limit_resume) {
                    aTemp.push(list[i])
                }
            }
            return aTemp;
        }
        function calculListeFull(list) {

            return list;
        }

        function constructTable(list) {
            var str = "";
            str += '<table width="99%"  border="1" cellspacing="0" cellpadding="0">'
            str += '<caption>' + list.length + ' enregistrements trouvés </caption>'
            str += '<tr>'
            str += '<th scope="col">&nbsp;Dernière évaluation</th>'
            str += '<th scope="col">&nbsp;Dossier</th>'
            str += '<th scope="col">&nbsp;Sexe</th>'
            str += '<th scope="col">&nbsp;Date de naissance</th>'
            str += '<th scope="col">&nbsp;Date de consentement</th>'
            str += '<th scope="col">&nbsp;Consulter</th>'
            str += '</tr>'
            for(var i in list) {
                str += '<tr>'
                str += '<td>&nbsp;' + list[i].date_fr + '</td>'
                str += '<td>&nbsp;' + list[i].dossier + '</td>'
                str += '<td>&nbsp;' + list[i].sexe + '</td>'
                str += '<td>&nbsp;' + list[i].dnaiss + '</td>'
                str += '<td>&nbsp;' + list[i].dconsentement + '</td>'
                str += '<td>&nbsp;<a href="' + list[i].link + '" target="_blank">Voir toutes les évaluations de ce dossier</a>'
                str += '</td>'
                str += '</tr>'
            }
            str += '</table>'
            $('#results').html(str);
            $('#results caption').html(list.length + " enregistrements trouvés " + ((_list_mode == "resume") ? "dans les 6 derniers mois" : "depuis le début de l'activité"))
        }
        function viewBtnResume() {
            $('#results').append('<div style="cursor:pointer; text-align:center; width:100%; margin:20px auto;" id="view_all"><a>VOIR TOUTES LES EVALUATIONS</a></div>')
            $('#view_all').on('click', clickBtnResume)
        }
        function hideBtnResume() {
            $('#view_all').css('display', 'none')
            $('#view_all').unbind('click')
        }



        $('#orderby').on('change', function() {
            _list_order = $(this).val();

            var current_list = calculListe();
            constructTable(current_list);
            if(_list_mode == "full") {
                hideBtnResume()
            }
            else {
                viewBtnResume()
            }
        })

        $('#fitlerDossier').on('keyup', function() {
            _list_mode = "full";
            _list_search = $(this).val();
            var current_list = calculListe();
            constructTable(current_list);
            hideBtnResume()
        })

        function clickBtnResume() {
            _list_mode = "full"
            var current_list = calculListe();
            constructTable(current_list);
            hideBtnResume()
        }

    })
</script>
