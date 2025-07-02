<!DOCTYPE html>


<!--Description of dashboard page

@author Ashan Rathsara-->


<html lang="en">

    <title><?php echo $title ?></title>

    <head>
        <!-- Styles -->
        <?php $this->load->view('template/css.php'); ?>


    </head>

    <body class="infobar-offcanvas">

        <!--header-->

        <?php $this->load->view('template/header.php'); ?>

        <!--end header-->

        <div id="wrapper">
            <div id="layout-static">

                <!--dashboard side-->

                <?php $this->load->view('template/dashboard_side.php'); ?>

                <!--dashboard side end-->

                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <ol class="breadcrumb">

                                <li class=""><a href="index.html">HOME</a></li>
                                <li class="active"><a href="index.html">DEDUCTION</a></li>

                            </ol>


                            <div class="page-tabs">
                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab1">DEDUCTION TYPES</a></li>
                                    <li><a data-toggle="tab" href="#tab2">VIEW DEDUCTION</a></li>

                                </ul>
                            </div>
                            <div class="container-fluid">


                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">

                                        <div class="row">
                                            <div class="col-xs-12">


                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-info">
                                                            <div class="panel-heading"><h2>ADD DEDUCTION</h2></div>
                                                            <div class="panel-body">
                                                                <form class="form-horizontal" id="frm_deduction_types" name="frm_deduction_types" action="<?php echo base_url(); ?>Master/Deduction_Types/insert_data" method="POST">
                                                                    <div class="form-group col-sm-12">
                                                                        <div class="col-sm-8">
                                                                            <img style="margin-left: 30%; width: 100px; height: 100px;" src="<?php echo base_url(); ?>assets/images/deduction_types.png" >
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="focusedinput" class="col-sm-4 control-label">Deduction Type Name</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" id="txt_deduction" name="txt_deduction" placeholder="Ex: Telephone Deduction">
                                                                        </div>

                                                                    </div>


                                                                    <div class="form-group col-sm-6 icheck-flat">
                                                                        <label class="col-sm-2 control-label"></label>
                                                                        <div class="col-sm-8 icheck-flat">
                                                                            <label class="checkbox green icheck col-sm-5">
                                                                                <input type="checkbox" id="isFixed" value="1"> IS FIXED
                                                                            </label>
                                                                            <label class="checkbox-inline icheck col-sm-5">
                                                                                <input type="checkbox" id="isFixed" value="1"> IS ACTIVE
                                                                            </label>
                                                                        </div>
                                                                    </div>



                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-sm-offset-2">
                                                                            <button type="submit" id="submit"  class="btn-primary btn fa fa-check">&nbsp;&nbsp;Submit</button>
                                                                            <button type="button" id="Cancel" name="Cancel" class="btn btn-danger-alt fa fa-times-circle">&nbsp;&nbsp;Cancel</button>
                                                                        </div>
                                                                    </div>

                                                                </form>
                                                                <hr>




                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <!--					<div class="tab-content">
                                                                                        <div class="tab-pane active" id="horizontal-form">
                                                                                                <form class="form-horizontal">
                                                                                                        <div class="form-group">
                                                                                                                <label for="focusedinput" class="col-sm-3 control-label">Designation Code</label>
                                                                                                                <div class="col-sm-8">
                                                                                                                        <input type="text" class="form-control" id="focusedinput" placeholder="Default Input">
                                                                                                                </div>
                                                                                                                
                                                                                                        </div>
                                                                                                </form>
                                                                                        </div>
                                                                                        
                                                                                </div>-->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab2">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading"><h2>Primary</h2></div>
                                                    <div class="panel-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab3">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-success">
                                                    <div class="panel-heading"><h2>Success</h2></div>
                                                    <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab4">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-info">
                                                    <div class="panel-heading"><h2>Info</h2></div>
                                                    <div class="panel-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab5">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-danger">
                                                    <div class="panel-heading"><h2>Danger</h2></div>
                                                    <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab6">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-warning">
                                                    <div class="panel-heading"><h2>Warning</h2></div>
                                                    <div class="panel-body">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab7">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-inverse">
                                                    <div class="panel-heading"><h2>Inverse</h2></div>
                                                    <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div> <!-- .container-fluid -->
                        </div>
                        <footer role="contentinfo">
                            <div class="clearfix">
                                <ul class="list-unstyled list-inline pull-left">
                                    <li><h6 style="margin: 0;"> &copy; 2015 Avenger</h6></li>
                                </ul>
                                <button class="pull-right btn btn-link btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>


            <div class="infobar-wrapper scroll-pane">
                <div class="infobar scroll-content">

                    <div id="widgetarea">

                        <div class="widget" id="widget-sparkline">
                            <div class="widget-heading">
                                <a href="javascript:;" data-toggle="collapse" data-target="#sparklinestats"><h4>Sparkline Stats</h4></a>
                            </div>
                            <div id="sparklinestats" class="collapse in">
                                <div class="widget-body">
                                    <ul class="sparklinestats">
                                        <li>
                                            <div class="title">Earnings</div>
                                            <div class="stats">$22,500</div>
                                            <div class="sparkline" id="infobar-earningsstats" style=""></div>
                                        </li>
                                        <li>
                                            <div class="title">Orders</div>
                                            <div class="stats">4,750</div>
                                            <div class="sparkline" id="infobar-orderstats" style=""></div>
                                        </li>
                                    </ul>
                                    <a href="#" class="more">More Sparklines</a>
                                </div>
                            </div>
                        </div>

                        <div class="widget">
                            <div class="widget-heading">
                                <a href="javascript:;" data-toggle="collapse" data-target="#recentactivity"><h4>Recent Activity</h4></a>
                            </div>
                            <div id="recentactivity" class="collapse in">
                                <div class="widget-body">
                                    <ul class="recent-activities">
                                        <li>
                                            <div class="avatar">
                                                <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                            </div>
                                            <div class="content">
                                                <span class="msg"><a href="#" class="person">Jean Alanis</a> invited 3 unconfirmed members</span>
                                                <span class="time">2 mins ago</span>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="avatar">
                                                <img src="assets/demo/avatar/avatar_09.png" class="img-responsive img-circle">
                                            </div>
                                            <div class="content">
                                                <span class="msg"><a href="#" class="person">Anthony Ware</a> is now following you</span>
                                                <span class="time">4 hours ago</span>

                                            </div>
                                        </li>
                                        <li>
                                            <div class="avatar">
                                                <img src="assets/demo/avatar/avatar_04.png" class="img-responsive img-circle">
                                            </div>
                                            <div class="content">
                                                <span class="msg"><a href="#" class="person">Bruce Ory</a> commented on <a href="#">Dashboard UI</a></span>
                                                <span class="time">16 hours ago</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="avatar">
                                                <img src="assets/demo/avatar/avatar_01.png" class="img-responsive img-circle">
                                            </div>
                                            <div class="content">
                                                <span class="msg"><a href="#" class="person">Roxann Hollingworth</a>is now following you</span>
                                                <span class="time">Feb 13, 2015</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <a href="#" class="more">See all activities</a>
                                </div>
                            </div>
                        </div>

                        <div class="widget" >
                            <div class="widget-heading">
                                <a href="javascript:;" data-toggle="collapse" data-target="#widget-milestones"><h4>Milestones</h4></a>
                            </div>
                            <div id="widget-milestones" class="collapse in">
                                <div class="widget-body">
                                    <div class="contextual-progress">
                                        <div class="clearfix">
                                            <div class="progress-title">UI Design</div>
                                            <div class="progress-percentage">12/16</div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-lime" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div class="contextual-progress">
                                        <div class="clearfix">
                                            <div class="progress-title">UX Design</div>
                                            <div class="progress-percentage">8/24</div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-orange" style="width: 33.3%"></div>
                                        </div>
                                    </div>
                                    <div class="contextual-progress">
                                        <div class="clearfix">
                                            <div class="progress-title">Frontend Development</div>
                                            <div class="progress-percentage">8/40</div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-purple" style="width: 20%"></div>
                                        </div>
                                    </div>
                                    <div class="contextual-progress m0">
                                        <div class="clearfix">
                                            <div class="progress-title">Backend Development</div>
                                            <div class="progress-percentage">24/48</div>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-danger" style="width: 50%"></div>
                                        </div>
                                    </div>
                                    <a href="#" class="more">See All</a>
                                </div>
                            </div>
                        </div>

                        <div class="widget">
                            <div class="widget-heading">
                                <a href="javascript:;" data-toggle="collapse" data-target="#widget-contact"><h4>Contacts</h4></a>
                            </div>
                            <div id="widget-contact" class="collapse in">
                                <div class="widget-body">
                                    <ul class="contact-list">
                                        <li id="contact-1">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_02.png" alt=""><span>Jeremy Potter</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-1">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">Jeremy Potter</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                        <li id="contact-2">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_07.png" alt=""><span>David Tennant</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-2">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">David Tennant</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                        <li id="contact-3">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_03.png" alt=""><span>Anna Johansson</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-3">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">Anna Johansson</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                        <li id="contact-4">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_09.png" alt=""><span>Alan Doyle</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-4">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">Alan Doyle</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                        <li id="contact-5">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_05.png" alt=""><span>Simon Corbett</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-5">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">Simon Corbett</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                        <li id="contact-6">
                                            <a href="javascript:;"><img src="assets/demo/avatar/avatar_01.png" alt=""><span>Polly Paton</span></a>
                                            <!-- <div class="contact-card contactdetails" data-child-of="contact-6">
                                                <div class="avatar">
                                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                                </div>
                                                <span class="contact-name">Polly Paton</span>
                                                <span class="contact-status">Client Representative</span>
                                                <ul class="details">
                                                    <li><a href="#"><i class="fa fa-envelope-o"></i>&nbsp;p.bateman@gmail.com</a></li>
                                                    <li><i class="fa fa-phone"></i>&nbsp;+1 234 567 890</li>
                                                    <li><i class="fa fa-map-marker"></i>&nbsp;Hollywood Hills, California</li>
                                                </ul>
                                            </div> -->
                                        </li>
                                    </ul>
                                    <a href="#" class="more">See All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!-- Load site level scripts -->

            <?php $this->load->view('template/js.php'); ?>							<!-- Initialize scripts for this page-->

            <!-- End loading page level scripts-->

    </body>


</html>