<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Me And My Friends</title>

    <!-- Bootstrap Core CSS -->
    <link href="library/bootstrap/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="library/bootstrap/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="library/bootstrap/dist/css/sb-admin-2.css" rel="stylesheet">

    <link href="library/bootstrap/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="library/bootstrap/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo baseUrl;?>">Me And My Friends</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li>
                Welcome <?php echo $_SESSION['first_name']." !!";?>
            </li>
            <li>
                <a href="createPost"><p class="fa fa-pencil"> Add post</p></a>
            </li>
            <!-- /.dropdown -->
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="login/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->

        </ul>
        <!-- /.navbar-top-links -->

    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- /.navbar-static-side -->

                    <h1 class="page-header">Your Feeds</h1>

                    <?php if(count($posts) >0) {?>
                        <ul class="timeline" id="newsFeeds">

                            <?php
                            $i=0;
                            foreach($posts as $post){?>
                                <li <?php if($i%2!=0) echo 'class="timeline-inverted"'?>>
                                    <div class="timeline-badge">
                                        <img class='img-circle' src="<?php echo "http://graph.facebook.com/".$post['facebook_id']."/picture"; ?>"/>
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <p><small class="text-muted"><i class="fa fa-clock-o"></i>  <?php echo $post['created_date']; ?></small></p>
                                        </div>
                                        <div class="timeline-body">
                                            <p><?php echo $post['content'];?></p>
                                            <p style="text-align: right">Posted By:<?php echo $post['post_by']; ?></p>
                                        </div>
                                    </div>
                                </li>
                                <?php $i++; }?>
                        </ul>
                    <?php } else {
                        echo "oops no news feed for you!!!";
                    } ?>

                    <?php if($loadNextPage){?>
                        <button type="button" style="margin-bottom: 10px;" id="showMore" data-page-number="1" class="btn btn-success">Show More</button>
                    <?php }?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;border-top: 1px solid #e7e7e7;">
    </nav>

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="library/bootstrap/bower_components/jquery/dist/jquery.min.js"></script>
<script src="library/bootstrap/dist/js/select2.min.js"></script>
<script src="library/bootstrap/dist/js/me-and-my-friend.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="library/bootstrap/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="library/bootstrap/bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="library/bootstrap/dist/js/sb-admin-2.js"></script>

</body>

</html>
