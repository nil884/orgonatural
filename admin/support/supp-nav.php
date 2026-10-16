<?
$getstaffCnt=selectQuery(SUPPORTSTAFF,"count(emp_id) as staffcnt","acc_type!='Client' AND isdel='0'");
?>
<div class="card">
    <div class="card-header-tab card-header"><div class="card-header-title">Customer Helpdesk Configuration</div></div>
    <div class="card-body cc-padding-bottom-0">
        <ul class="nav btnlist  marginbottom0">
            <li class="nav-item"><a href="<?=ADMINURL; ?>presetting.php" class="btn btn-default btn-sm"> <i class="fa fa-home" aria-hidden="true"></i> </a></li>
            <li class="nav-item"> <a href="supportemail.php" class="btn btn-default btn-sm"> Master Configuration</a></li>
            <li class="nav-item"><a href="adddept.php" class="btn btn-default btn-sm">Add New Department</a></li>
            <li class="nav-item"><a  href="<?=ADMINURL; ?>support/staffgrouplist.php" class="btn btn-default btn-sm">Manage Staff Group</a></li>
            <li class="nav-item"><a href="<?=ADMINURL; ?>support/staff.php" class="btn btn-default btn-sm">Manage Staff Members (<?=$getstaffCnt[0]['staffcnt'];?>)</a></li>
        </ul>
    </div>
</div>