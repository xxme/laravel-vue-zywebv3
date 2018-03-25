<template>
  <!-- universe_nav -->
    <div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
        <li class="active"><a href="#quicknav" data-toggle="tab" aria-expanded="true">{{ __('messages.quicknav') }}</a></li>
        <li class=""><a href="#topcalendar" data-toggle="tab" aria-expanded="false">{{ __('messages.eventcalendar') }}</a></li>
        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">{{ __('messages.timeline') }}</a></li>
        <li class=""><a href="#statistics" data-toggle="tab" aria-expanded="true">{{ __('messages.statistics') }}</a></li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane active" id="quicknav">
            <!-- quicknav -->
            <div class="row margin">
            <button type="button" class="btn bg-olive"><i class="fa fa-list-alt"></i> {{ __('messages.shoppinglist') }}</button>
            <button type="button" class="btn btn-primary"><i class="fa fa-money"></i> {{ __('messages.estimateslist') }}</button>
            <button type="button" class="btn bg-purple"><i class="fa fa-empire"></i> {{ __('messages.tasklist') }}</button>
            <button type="button" class="btn btn-info"><i class="fa fa-recycle"></i> {{ __('messages.recycle') }}</button>
            </div>
            <!-- ./quicknav -->
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="topcalendar">
            <div id="topcalendardiv"></div>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">
            <!-- timeline time label -->
            <li class="time-label">
                    <span class="bg-red">
                    10 Feb. 2014
                    </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-envelope bg-blue"></i>

                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                <div class="timeline-body">
                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                    quora plaxo ideeli hulu weebly balihoo...
                </div>
                <div class="timeline-footer">
                    <a class="btn btn-primary btn-xs">Read more</a>
                    <a class="btn btn-danger btn-xs">Delete</a>
                </div>
                </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-user bg-aqua"></i>

                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                </h3>
                </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-comments bg-yellow"></i>

                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                <div class="timeline-body">
                    Take me to your leader!
                    Switzerland is small and neutral!
                    We are more like Germany, ambitious and misunderstood!
                </div>
                <div class="timeline-footer">
                    <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                </div>
                </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline time label -->
            <li class="time-label">
                    <span class="bg-green">
                    3 Jan. 2014
                    </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
                <i class="fa fa-camera bg-purple"></i>

                <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                <div class="timeline-body">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                    <img src="http://placehold.it/150x100" alt="..." class="margin">
                </div>
                </div>
            </li>
            <!-- END timeline item -->
            <li>
                <i class="fa fa-clock-o bg-gray"></i>
            </li>
            </ul>
        </div>
        <div class="tab-pane" id="statistics">
            <!-- statistics -->
            <div class="box-header">
            <h3 class="box-title">收支情况</h3>
            </div>
            <table class="table table-striped">
            <tbody>
                <tr>
                <td style="width: 50%">总收入:￥1,264,500</td>
                <td>总支出:￥1,264,500</td>
                </tr>
                <tr>
                <td>高速支出:￥0</td>
                <td>加油支出:￥0</td>
                </tr>
                <tr>
                <td>停车支出:￥0</td>
                <td>餐饮支出:￥0</td>
                </tr>
                <tr>
                <td>买货支出:￥0</td>
                <td>其它支出:￥0</td>
                </tr>
                <tr>
                <td>返现日元支出:￥0</td>
                <td>返现RMB支出:￥0</td>
                </tr>
                <tr>
                <td colspan="2">未完成收入预计:￥2,319,800</td>
                </tr>
            </tbody>
            </table>
            <div class="box-header">
            <h3 class="box-title">売上排行榜</h3>
            </div>
            <table class="table table-striped">
            <tbody>
                <tr>
                <th style="width: 50px">排名</th>
                <th style="width: 150px">超人</th>
                <th>金额</th>
                <th>已完成</th>
                <th style="width: 80px">百分比</th>
                </tr>
                <tr>
                <td>1.</td>
                <td>Update software</td>
                <td>￥3,101,000</td>
                <td>￥1,025,000</td>
                <td><span class="badge bg-red">55%</span></td>
                </tr>
                <tr>
                <td>2.</td>
                <td>Clean database</td>
                <td>￥3,101,000</td>
                <td>￥1,025,000</td>
                <td><span class="badge bg-yellow">70%</span></td>
                </tr>
                <tr>
                <td>3.</td>
                <td>Cron job running</td>
                <td>￥3,101,000</td>
                <td>￥1,025,000</td>
                <td><span class="badge bg-light-blue">30%</span></td>
                </tr>
                <tr>
                <td>4.</td>
                <td>Fix and squish bugs</td>
                <td>￥3,101,000</td>
                <td>￥1,025,000</td>
                <td><span class="badge bg-green">90%</span></td>
                </tr>
            </tbody>
            </table>
            <!-- ./statistics -->
        </div>
        <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    </div>
    <!-- /.universe_nav -->
</template>
