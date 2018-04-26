<template>
  <!-- universe_nav -->
    <div class="margin">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li :class="[otherMonth ? '' : 'active']"><a href="#quicknav" data-toggle="tab" aria-expanded="true">{{ $t('topmenu.quicknav') }}</a></li>
                <li :class="[otherMonth ? 'active' : '']"><a href="#topcalendar" data-toggle="tab" aria-expanded="false">{{ $t('topmenu.eventcalendar') }}</a></li>
                <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">{{ $t('topmenu.timeline') }}</a></li>
                <li class=""><a href="#statistics" data-toggle="tab" aria-expanded="true">{{ $t('topmenu.statistics') }}</a></li>
            </ul>
            <div class="tab-content">
                <div :class="[otherMonth ? '' : 'active', 'tab-pane']" id="quicknav">
                    <!-- quicknav -->
                    <div class="row margin">
                        <button type="button" class="btn bg-olive"><i class="fa fa-list-alt"></i> {{ $t('nav.shoppinglist') }}</button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-money"></i> {{ $t('nav.estimateslist') }}</button>
                        <button type="button" class="btn bg-purple"><i class="fa fa-empire"></i> {{ $t('nav.tasklist') }}</button>
                        <button type="button" class="btn btn-info"><i class="fa fa-recycle"></i> {{ $t('nav.recycle') }}</button>
                    </div>
                    <!-- ./quicknav -->
                </div>
                <!-- /.tab-pane -->
                <div :class="[otherMonth ? 'active' : '', 'tab-pane']" id="topcalendar">
                    <todo :events="events" :showYm="showYm" :holidays="holidays" id="calendartodo"></todo>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <ul v-if="log_list.length > 0" class="timeline timeline-inverse">
                        <!-- timeline time label -->
                        <li class="time-label">
                            <span class="bg-red">
                                {{ timelineYmd }}
                            </span>
                        </li>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <li v-for="(log, key) in log_list" :key="log.id">
                            <i :class="['fa', { 'fa-calendar bg-blue': log.type == 1, 'fa-book bg-aqua': log.type == 2, 'fa-comments bg-yellow': log.type == 3 }]"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ log.created_at }}</span>

                                <h3 class="timeline-header">
                                    <a href="#">{{ log.user.name }}</a> 
                                    <span v-if="log.log_type == 1">{{ $t('global.addEvent') }} </span>
                                    <span v-else-if="log.log_type == 2">{{ $t('global.update') }} </span>
                                    <span v-else-if="log.log_type == 3">{{ $t('global.delete') }} </span>
                                    <span v-if="log.type == 1">{{ log.content }} </span>
                                    <span v-if="log.type == 1">{{ $t('event.event') }} </span>
                                    <span v-else-if="log.type == 2">{{ $t('quicknav.typeofwork') }} </span>
                                    <span v-else-if="log.type == 3">{{ $t('event.comment') }} </span>
                                    <span v-if="log.type == 1">#{{ log.obj_id }}</span>
                                </h3>
                                <div v-if="log.type == 3" class="timeline-body">
                                    <span>{{ log.content }}</span>
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
<script>
import Vue from 'vue'
import moment from 'moment'

Vue.component('todo', {
    template: '<div></div>',
    props: ['events', 'showYm', 'holidays'],
    data () {
        return {
            cal: null
        }
    },
    mounted () {
        var self = this
        this.cal = $(self.$el)
        var router = this.$router
        
        var args = {
            defaultView: 'month',
            events: self.events,
            defaultDate: self.showYm,
            minTime: '08:00:00',
            maxTime: '21:00:00',
            header: {
                left: 'title createEventButton',
                right: 'month agendaWeek agendaDay today prev,next'
            },
            // 日付クリックイベント
            dayClick: function(date, jsEvent, view) {
                window.location.hash = date.format();
            },
            navLinks: true,
            navLinkDayClick: function(date, jsEvent) {
                router.push({ path: '/admin/event/create/' + date.format() })
            },
            // イベントクリック
            eventClick: function(calEvent, jsEvent, view) {
                self.$parent.$emit('makered', calEvent.id);
                window.location.hash = 'event' + calEvent.id;
            },
            viewRender: function(view, element){
                var showdate = $('#calendartodo').fullCalendar('getDate');
                self.$parent.$emit('update-events', moment(showdate).format('YYYY-MM'));
            },
            customButtons: {
                createEventButton: {
                    text: this.$i18n.t('event.addEvent'),
                    click: function() {
                        router.push({ path: '/admin/event/create' })
                    }
                }
            }
        }
        
        this.cal.fullCalendar(args)
    },
    watch: {
        getevents: function(val) {
            this.cal.fullCalendar('removeEventSources');
            this.cal.fullCalendar('addEventSource', this.events);
            
            Object.keys(this.holidays).forEach(function (holiday) {
                $("td[data-date = '"+holiday+"']").css("background-color", "#ffe6e6");
            });
        }
    },
    computed: {
        getevents: function() {
            return this.events;
        }
    }
})

export default {
  props: ['otherMonth', 'showYm', 'events', 'holidays'],
  data() {
    return {
        timelineYmd: this.showYm,
        log_list: [],
    }
  },
  mounted() { 
      this.get_logs(this.timelineYmd);
  },
  watch: {
    timelineYmd: function (value) {
      this.get_logs(value);
    }
  },
  methods: {
    get_logs(ymd) {
        this.$http({
            url: '/admin/ajaxlogs/' + ymd,
            method: 'GET'
        }).then(res =>  {
            this.log_list = res.data;
            console.log(this.log_list);
        })
    }
  }
}
</script>
