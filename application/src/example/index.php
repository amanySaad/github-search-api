<?php
require_once "../../vendor/autoload.php";
use AmanySaad\GithubSearchApi\Api\Search;
use AmanySaad\GithubSearchApi\SearchGithub;
include('paginator.php');
if($_GET){
   
    $q = (isset($_REQUEST["language"])?"language:".$_REQUEST["language"]:"language:php");
    $date =(!empty($_REQUEST["date"])?">".$_REQUEST["date"]:false);
    $order = (isset($_REQUEST["order"])?$_REQUEST["order"]:'desc');
    $per_page = (isset($_REQUEST["per_page"])?$_REQUEST["per_page"]:100);
    $repositories= SearchGithub::create()->getSearch()->findRepositories($q, $per_page, Search::SORT_BY_STARS,$order, $date);
}else{
    $repositories= SearchGithub::create()->getSearch()->findRepositories('language:php',50 ,Search::SORT_BY_STARS);

}
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="container">
    <h1> Display popular repositories on GitHub.</h1>
    <hr>
    <form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>" class="form-inline">
        <div class="form-group">
        <select class="form-control" name="language"  class="form-control">
            <option value="php">PHP</option>
             <option value="java">Java</option>
             
        </select>
        </div>
        <div class="form-group">
        <select class="form-control" name="per_page" class="form-control">
            <option value="10">10</option>
             <option value="50">50</option>
              <option value="100">100</option>
            
        </select>
        </div>
        <div class="form-group">
        <select class="form-control" name="order" class="form-control">
            <option value="desc">Descending</option>
             <option value="asc">Ascending</option>
            
            
        </select>
        </div>
        <div class="form-group">
        <input class="form-control" type="date" name="date" placeholder="choose date"/>
        </div>
        <input class="form-control" type="submit" value="Search" />
    </form>
    <hr>
    <?php
    if(count($repositories)>0) {
        
        $pages = new Paginator;
        $pages->default_ipp = 15;
        $pages->items_total = count($repositories);
        $pages->mid_range = 9;
        $pages->paginate();  
         
    }
    ?>
    <div class="clearfix"></div>
     
    <div class="row marginTop">
        <div class="col-sm-12 paddingLeft pagerfwt">
            <?php if($pages->items_total > 0) { ?>
                <?php echo $pages->display_pages();?>
                <?php echo $pages->display_items_per_page();?>
                <?php echo $pages->display_jump_menu(); ?>
            <?php }?>
        </div>
        <div class="clearfix"></div>
    </div>
 
    <div class="clearfix"></div>
     
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>id#</th>
                <th>avatar url</th>
                <th>Full Name</th>
                <th> url</th>
                <th> description</th>
                <th>owner</th>
                <th>Language</th>
            
            </tr>
        </thead>
        <tbody>
            <?php
            if($pages->items_total>0){
                $n  =   1;
            foreach ($repositories as $i => $repository) {
            ?>
            <tr>
                <td><?php echo $repository->getId(); ?></td>
                <td><img height="50px" width="50px" src="<?php echo $repository->getOwner()->getAvatarUrl();?>" class="rounded-circle"></td>
                <td><?php echo $repository->getFullName() ; ?></td>
                <td><?php echo '<a href="https://github.com/.'.$repository->getFullName().'">GO</a>'?></td>
                <td><?php echo $repository->getDescription() ; ?></td>
                <td><?php echo $repository->getOwner()->getLogin(); ?></td>
                <td><?php echo $repository->getLanguage(); ?></td>
               
            </tr>
            <?php
                }
            }else{?>
            <tr>
                <td colspan="6" align="center"><strong>No Repositories Found!</strong></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
     
    <div class="clearfix"></div>
     
    <div class="row marginTop">
        <div class="col-sm-12 paddingLeft pagerfwt">
            <?php if($pages->items_total > 0) { ?>
                <?php echo $pages->display_pages();?>
                <?php echo $pages->display_items_per_page();?>
                <?php echo $pages->display_jump_menu(); ?>
            <?php }?>
        </div>
        <div class="clearfix"></div>
    </div>
 
    <div class="clearfix"></div>
     
</div> <!--/.container-->
