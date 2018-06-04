<template>
  <!-- universe_nav -->
    <div>
        <div class="marginb5">
            <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin/estimate')"><i class="fa fa-comments-o"></i> {{ $t('nav.estimateslist') }}</button>
            <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin/productlist')"><i class="fa fa-shopping-cart"></i> {{ $t('nav.shoppinglist') }}</button>
            <button type="button" class="btn btn-outline-primary" v-if="auth && auth.group_id == 1" @click="$router.push('/admin/finances')"><i class="fa fa-money"></i> {{ $t('nav.money') }}</button>
        </div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs" v-if="auth && auth.group_id == 1">
                <li class="active"><a href="#topcalendar" data-toggle="tab" aria-expanded="false">{{ $t('topmenu.eventcalendar') }}</a></li>
                <li><a href="#statistics" data-toggle="tab" aria-expanded="true">{{ $t('topmenu.statistics') }}</a></li>
            </ul>
            <div class="tab-content">
                <!-- /.tab-pane -->
                <div class="active tab-pane" id="topcalendar">
                    <todo :events="events" :showYmd="showYmd" :holidays="holidays" @updateym="updateym" id="calendartodo"></todo>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="statistics" v-if="auth && auth.group_id == 1">
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
                                <td>{{ $t('event.fxjpy') }}:{{ fee.finance.fxjpy | formatNumberJPY }}</td>
                                <td>{{ $t('event.fxrmb') }}:{{ fee.finance.fxrmb | formatNumberJPY }}</td>
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
            cal: null,
            showmonth : ""
        }
    },
    mounted () {
        var self = this
        this.cal = $(self.$el)
        // var router = this.$router
        
        var args = {
            defaultView: 'month',
            events: self.events,
            defaultDate: self.showYmd,
            minTime: '08:00:00',
            maxTime: '21:00:00',
            header: {
                left: 'title createEventButton',
                right: 'month agendaWeek agendaDay today tomorrowButton prev,next'
            },
            // 日付クリックイベント
            dayClick: function(date, jsEvent, view) {
                self.$parent.$emit('showdata', date.format());
                // window.location.hash = date.format();
            },
            // navLinks: true,
            // navLinkDayClick: function(date, jsEvent) {
            //     router.push({ path: '/admin/event/create/' + date.format() })
            // },
            // イベントクリック
            eventClick: function(calEvent, jsEvent, view) {
                // self.$parent.$emit('makered', calEvent.id);
                // window.location.hash = 'event' + calEvent.id;
                self.$parent.$emit('showitem', calEvent.id);
            },
            viewRender: function(view, element){
                var showdate = $('#calendartodo').fullCalendar('getDate');
                if(!self.showmonth || self.showmonth != showdate.format('YYYY-MM')) {
                    self.$parent.$emit('update-events', showdate.format('YYYY-MM'));
                    self.$emit('updateym', showdate);
                    self.showmonth = showdate.format('YYYY-MM');
                }
                if(showdate.format('YYYY-MM-DD') == moment().add(1, 'd').format('YYYY-MM-DD')) {
                    $(".fc-tomorrowButton-button").prop("disabled", true);
                    $(".fc-tomorrowButton-button").addClass('fc-state-disabled');
                } else {
                    $(".fc-tomorrowButton-button").prop("disabled", false);
                    $(".fc-tomorrowButton-button").removeClass('fc-state-disabled');
                }
            },
            eventRender: function(event, element) {
                self.showdescription(event, element);
            },
            customButtons: {
                createEventButton: {
                    text: this.$i18n.t('event.addEvent'),
                    click: function() {
                        self.$parent.$emit('showform');
                    }
                },
                tomorrowButton: {
                    text: this.$i18n.t('global.tomorrow'),
                    click: function() {
                        self.gotoDate();
                    }
                }
            }
        }
        
        this.cal.fullCalendar(args).on('click', '.fc-month-button', function() {
            self.resetFirstDay();
        }).on('click', '.fc-agendaWeek-button', function(){
            self.setFirstDay();
        });
    },
    watch: {
        getevents: function(val) {
            this.cal.fullCalendar('removeEventSources');
            this.cal.fullCalendar('addEventSource', this.events);
            
            // Object.keys(this.holidays).forEach(function (holiday) {
            //     $("td[data-date = '"+holiday+"']").css("background-color", "#ffe6e6");
            // });
        },
        showYmd(val) {
            this.cal.fullCalendar('gotoDate', this.showYmd);
        }
    },
    methods: {
        gotoDate() {
            $(this.$el).fullCalendar('gotoDate', moment().add(1, 'd'));
        },
        setFirstDay() {
            $(this.$el).fullCalendar('option', 'firstDay', moment().subtract(1, 'd').day());
        },
        resetFirstDay() {
            $(this.$el).fullCalendar('option', 'firstDay', 1);
        },
        showdescription(event, element) {
            element.find('.fc-title').append("<br/>" + event.description); 
        }
    },
    computed: {
        getevents: function() {
            return this.events;
        }
    }
})

export default {
  props: ['showYmd', 'events', 'holidays', 'fee', 'auth'],
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
    },
    holidays() {
        for(var index in this.holidays) {
            $('.fc-day[data-date="'+this.holidays[index].date+'"]').css('background-color', '#e06c97');
        }
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
