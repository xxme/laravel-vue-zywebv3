<template>
  <!-- universe_nav -->
    <div>
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
                        <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin/productlist')"><i class="fa fa-shopping-cart"></i> {{ $t('nav.shoppinglist') }}</button>
                        <button type="button" class="btn btn-outline-primary"><i class="fa fa-money"></i> {{ $t('nav.estimateslist') }}</button>
                        <button type="button" class="btn btn-outline-primary"><i class="fa fa-empire"></i> {{ $t('nav.tasklist') }}</button>
                    </div>
                    <!-- ./quicknav -->
                </div>
                <!-- /.tab-pane -->
                <div :class="[otherMonth ? 'active' : '', 'tab-pane']" id="topcalendar">
                    <todo :events="events" :showYmd="showYmd" :holidays="holidays" @updateym="updateym" id="calendartodo"></todo>
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
                            <i :class="['fa', {'bg-blue' : log.log_type == 1, 'bg-yellow' : log.log_type == 2, 'bg-red' : log.log_type == 3}, { 'fa-calendar': log.type == 1, 'fa-book bg-aqua': log.type == 2, 'fa-comments': log.type == 3, 'fa-check-square-o bg-green': log.type == 6, 'fa-shopping-cart': log.type == 7 }]"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ log.created_at | formatTime }}</span>

                                <h3 class="timeline-header">
                                    <a href="#">{{ log.user.name }}</a> 
                                    <span v-if="log.log_type == 1 && log.type == 6">{{ $t('event.complete') }}{{ $t('event.event') }} </span>
                                    <span v-else-if="log.log_type == 2 && log.type == 6">{{ $t('global.update') }}{{ $t('event.complete') }}{{ $t('global.data') }} </span>
                                    <span v-else-if="log.log_type == 1">{{ $t('global.add') }} </span>
                                    <span v-else-if="log.log_type == 2">{{ $t('global.update') }} </span>
                                    <span v-else-if="log.log_type == 3">{{ $t('global.delete') }} </span>
                                    <span v-if="log.type == 1">{{ log.content }} </span>
                                    <span v-if="log.type == 1">{{ $t('event.event') }} </span>
                                    <span v-else-if="log.type == 2">{{ $t('quicknav.typeofwork') }} </span>
                                    <span v-else-if="log.type == 3">{{ $t('event.comment') }} </span>
                                    <span v-else-if="log.type == 7">{{ $t('productlist.pagetitle') }} </span>
                                    <span v-if="log.type == 1 || log.type == 7">#{{ log.obj_id }}</span>
                                </h3>
                                <div v-if="log.type == 3 || log.type == 6 || log.type == 7" class="timeline-body">
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
                        <h3 class="box-title">{{ showYm }}{{ $t('finance.finance') }}</h3>
                    </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr v-if="fee.finance.total || fee.finance.expenditure">
                                <td style="width: 50%">{{ $t('finance.total') }}:{{ fee.finance.total | formatNumberJPY }}</td>
                                <td>{{ $t('finance.expenditure') }}:{{ fee.finance.expenditure | formatNumberJPY }}</td>
                            </tr>
                            <tr v-if="fee.finance.zcgaosu || fee.finance.zcjiayou">
                                <td>{{ $t('finance.zcgaosu') }}:{{ fee.finance.zcgaosu | formatNumberJPY }}</td>
                                <td>{{ $t('finance.zcjiayou') }}:{{ fee.finance.zcjiayou | formatNumberJPY }}</td>
                            </tr>
                            <tr v-if="fee.finance.zctingche || fee.finance.zccanyin">
                                <td>{{ $t('finance.zctingche') }}:{{ fee.finance.zctingche | formatNumberJPY }}</td>
                                <td>{{ $t('finance.zccanyin') }}:{{ fee.finance.zccanyin | formatNumberJPY }}</td>
                            </tr>
                            <tr v-if="fee.finance.zcmaihuo || fee.finance.zcother">
                                <td>{{ $t('finance.zcmaihuo') }}:{{ fee.finance.zcmaihuo | formatNumberJPY }}</td>
                                <td>{{ $t('finance.zcother') }}:{{ fee.finance.zcother | formatNumberJPY }}</td>
                            </tr>
                            <tr v-if="fee.finance.fxjpy || fee.finance.fxrmb">
                                <td>{{ $t('finance.fxjpy') }}:{{ fee.finance.fxjpy | formatNumberJPY }}</td>
                                <td>{{ $t('finance.fxrmb') }}:{{ fee.finance.fxrmb | formatNumberJPY }}</td>
                            </tr>
                            <tr>
                            <td v-if="fee.finance.undone" colspan="2">{{ $t('finance.undone') }}:{{ fee.finance.undone | formatNumberJPY }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="box-header">
                        <h3 class="box-title">{{ showYm }}{{ $t('finance.proceeds') }}</h3>
                    </div>
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th style="width: 50px">{{ $t('finance.ranking') }}</th>
                                <th style="width: 150px">{{ $t('finance.name') }}</th>
                                <th>{{ $t('finance.amount') }}</th>
                                <th>{{ $t('finance.completed') }}</th>
                                <th style="width: 80px">{{ $t('finance.percentage') }}</th>
                            </tr>
                            <tr v-for="(user, key) in orderedFeeUsers" :key="user.id">
                                <td :class="'medals'+key"><span v-if="key > 2">{{ key + 1}}.</span><span v-else>.</span></td>
                                <td>
                                    <img v-if="user.profileimg" class="img-circle" width="30" :src="'/uploads/profiles/' + user.profileimg"> 
                                    <img v-else class="img-circle" width="30" src="/images/no-image-available.jpeg"> 
                                    {{ user.name }}
                                </td>
                                <td>{{ user.total | formatNumberJPY }}</td>
                                <td>{{ user.completed | formatNumberJPY }}</td>
                                <td><span :class="['badge', { 'bg-red': key == 0, 'bg-light-blue': key == 1, 'bg-green': key == 2, '': key > 2 }]">{{ percentage(user.total) }}</span></td>
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
    props: ['events', 'showYmd', 'holidays'],
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
            defaultDate: self.showYmd,
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
                self.$emit('updateym', showdate);
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
  props: ['otherMonth', 'showYmd', 'events', 'holidays', 'fee'],
  data() {
    return {
        timelineYmd: this.showYmd,
        log_list: [],
        showYm: this.$t('finance.thismonth'),
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
        })
    },
    percentage(usertotal) {
        return Math.round(usertotal / this.fee.finance.total * 100) + '%';
    },
    updateym(showymd) {
        var formated = moment(showymd).format('YYYY年MM月');
        if(formated == moment().format('YYYY年MM月')) {
            this.showYm = this.$t('finance.thismonth')
        } else {
            this.showYm = formated
        }
    }
  },
  filters: {
    formatTime(datetime) {
      return moment(datetime).format('HH:mm');
    },
    formatNumberJPY(number) {
      if(number) {
        return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
      } else {
        return '0';
      }
    }
  },
  computed: {
    orderedFeeUsers: function () {
        return _.orderBy(this.fee.users, 'total', 'desc')
    }
  }
}
</script>
<style scoped>
.medals0, .medals1, .medals2 {
    background-image: url("/images/medals.png");
    background-repeat: no-repeat;
    background-size: auto 90%;
    width: 33px;
    position: absolute;
    margin-top: 5px;
}
.medals1 {
    background-position: -35px 0;
}
.medals2 {
    background-position: -69px 0;
}
</style>
