<?php $getactivedept=selectQuery(SUPPORTDEPT,"dept_name","isActive='1' and isDel='0' order by dept_name ASC"); ?>
<div class="msg"></div>
<div class="row mb-3">
    <div class="col-md-12 col-sm-12 searchGuest">
        <form action ="searchresult.php" onsubmit=" return check()" method="post">
            <div class="row">
                <div class="form-group col-12 col-sm-4 col-md-4 col-lg-3">
                   <input type="text" class="form-control" id="searchfld" name="searchfld" placeholder="Query">
                </div>
                <div class="form-group col-6 col-sm-4 col-md-4 col-lg-3">
                    <select class="form-control" id="dept" name="dept" >
                        <option value="">Select Department</option>
                            <?php for($i=0;$i<count($getactivedept);$i++) { ?>
                        <option value="<?php echo $getactivedept[$i]['dept_name']; ?>"><?php echo $getactivedept[$i]['dept_name']; ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="form-group col-6 col-sm-4 col-md-4 col-lg-2">
                    <select class="form-control" id="sts" name="sts">
                        <option value="">Select Status</option>
                        <option>Open</option>
                        <option>Answered</option>
                        <option>Overdue</option>
                        <option>Closed</option>
                    </select>
                </div>
                <div class="form-group col-12 col-sm-6 col-md-4 col-lg-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
