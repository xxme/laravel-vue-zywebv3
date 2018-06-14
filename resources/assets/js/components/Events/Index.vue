<template>
  <div>
    <div>
      <section class="content-header">
        <h1>
          {{ $t('event.pagetitle') }}
          <span v-show="!showEventItem && !showformflag" class="pull-right">
            <div class="switch">
              <small>{{ $t('finance.completed') }}</small>
              <input id="switch" v-model="showCompleted" type="checkbox">
              <label for="switch"></label>
            </div>
          </span>
        </h1>
      </section>
      <!-- Main content -->
      <section class="content">
        <div class="marginb5">
          <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin/estimate')"><i class="fa fa-comments-o"></i> {{ $t('nav.estimateslist') }}</button>
          <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin/productlist')"><i class="fa fa-shopping-cart"></i> {{ $t('nav.shoppinglist') }}</button>
          <button type="button" class="btn btn-outline-primary" v-if="auth && auth.group_id == 1" @click="$router.push('/admin/finances')"><i class="fa fa-money"></i> {{ $t('nav.money') }}</button>
          <span v-show="!showEventItem && !showformflag" class="pull-right">
            <div class="switchestimates">
              <input id="switchestimates" v-model="switchEstimates" type="checkbox">
              <label for="switchestimates"></label>
            </div>
          </span>
        </div>
        <div class="nav-tabs-custom" v-show="!showformflag && showCalendarFlag">
          <div class="tab-content">
            <!-- /.tab-pane -->
            <div class="active tab-pane" id="topcalendar">
              <todo :events="eventDataSources" :showCompleted="showCompleted" :switchEstimates="switchEstimates" :showYmd="showYmd" :holidays="holidays" @updateym="updateym" @update-events="get_events" @showform="quickformswitch" @showitem="showitem" @reload="reloadEvents" id="calendartodo"></todo>
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

          <ul class="nav nav-tabs" v-if="auth && auth.group_id == 1">
            <li class="active"><a href="#topcalendar" data-toggle="tab" aria-expanded="false">{{ $t('topmenu.eventcalendar') }}</a></li>
            <li><a href="#statistics" data-toggle="tab" aria-expanded="true">{{ $t('topmenu.statistics') }}</a></li>
          </ul>
        </div>
        <event-item :eventdata="show_list" :auth="auth" :userlist="user_list" :showflag="showEventItem" :showCompleted="showCompleted" @editevent="editevent" @showCalendar="showCalendar" @completedevent="completedevent" @deleteevent="deleteevent" @copyevent="copyEvent"></event-item>
        <quick-form v-if="showformflag" :formoptions="formoptions" :eventdate="eventdate" :eventid="eventid" :copyid="copyid" @closeform="quickformswitch(false)" @addedevent="addedevent"></quick-form>
      </section>
    </div>
    <loading :loadingShow="loadingShow"></loading>
  </div>
</template>

<script>
import Vue from 'vue'
import moment from 'moment'
import Loading from '../Public/Loading'
import QuickForm from './QuickForm.vue';
import EventItem from './EventItem.vue';

export default {
  mounted() {
    this.get_formtypes()
  },
  data() {
    return {
      auth: null,
      events_list: [],
      user_list: [],
      show_list: [],
      eventDataSources: {
        estimateundone: [],
        estimatecompleted: [],
        ordinaryundone: [],
        ordinarycompleted: [],
      },
      eventdate: "",
      eventid: 0,
      copyid: 0,
      holidays: [],
      showCompleted: false,
      showQuickForm: false,
      showEventItem: false,
      showformflag: false,
      loadingShow: false,
      showCalendarFlag: true,
      switchEstimates: true,
      showYmd: moment().format('YYYY-MM-DD'),
      showYm: moment().format('YYYY年MM月'),
      formoptions: {
        worktype: [],
        careful: [],
        total: [],
        aboutgoods: [],
        truck: [],
        product_list: []
      },
      fee: {
        users: [],
        finance: {
          total: 0,
          undone: 0,
          expenditure: 0,
          zctingche: 0,
          zccanyin: 0,
          zcgaosu: 0,
          zcjiayou: 0,
          zcmaihuo: 0,
          zcother: 0,
          fxrmb: 0,
          fxjpy: 0
        }
      }
    }
  },
  components: {
    EventItem,
    Loading,
    QuickForm
  },
  methods: {
    get_events(ym, ymd = "") {
      this.loadingShow = true;
      this.events_list = [];
      $('#eventrs').html(this.$t('global.loading'));
      this.$http({
          url: '/admin/events/' + ym,
          method: 'GET'
      }).then(res =>  {
          this.holidays = res.data.holidays;
          this.auth = res.data.auth;
          for (var index in res.data.users) {
            var user = {};
            user.id = res.data.users[index].id;
            user.text = res.data.users[index].name;
            this.user_list.push(user);
          }
          if(res.data.events.length == 0) {
            $('#eventrs').html(this.$t('global.noRes'));
          } else {
            this.events_list = res.data.events;
            this.setEvents();
          }
          this.setFee();
          this.loadingShow = false;
      })
    },
    reloadEvents() {
      var ym = moment($('#calendartodo').fullCalendar('getDate')).format('YYYY-MM');
      this.get_events(ym);
    },
    showitem(id) {
      this.show_list = [];
      for(var index in this.events_list) {
        if(this.events_list[index].id == id) {
          this.show_list.push(this.events_list[index]);
        }
      }
      this.checkCountShowList();
    },
    checkCountShowList() {
      if(this.show_list.length > 0) {
        this.showCalendarFlag = false;
        this.showEventItem = true;
      }
    },
    resetFee() {
      this.fee.users = [];
      this.fee.finance.total = 0;
      this.fee.finance.undone = 0;
      this.fee.finance.expenditure = 0;
      this.fee.finance.zctingche = 0;
      this.fee.finance.zccanyin = 0;
      this.fee.finance.zcgaosu = 0;
      this.fee.finance.zcjiayou = 0;
      this.fee.finance.zcmaihuo = 0;
      this.fee.finance.zcother = 0;
      this.fee.finance.fxrmb = 0;
      this.fee.finance.fxjpy = 0;
    },
    // nav finance
    setFee() {
      this.resetFee();
      for(var i in this.events_list) {
        if(this.events_list[i].amount || this.events_list[i].expense) {
          var hasuserdata = false;
          for(var index in this.fee.users) {
            if(this.fee.users[index].id == this.events_list[i].user.id) {
              hasuserdata = true;
              if(this.events_list[i].expense) {
                // completed
                this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].expense.finalprice);
                this.fee.users[index].completed = parseInt(this.fee.users[index].completed) + parseInt(this.events_list[i].expense.finalprice);
              } else {
                // undone
                if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
                  this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].amount);
                }
              }
            }
          }
          if(!hasuserdata && (this.events_list[i].amount || this.events_list[i].expense)) {
            var feeuser = new Object();
            feeuser.id = this.events_list[i].user.id;
            feeuser.name = this.events_list[i].user.name;
            if(this.events_list[i].user.profileimg) {
              feeuser.profileimg = this.events_list[i].user.profileimg;
            }
            if(this.events_list[i].expense) {
              // completed
              feeuser.total = this.events_list[i].expense.finalprice;
              feeuser.completed = this.events_list[i].expense.finalprice;
            } else {
              // undone
              if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
                feeuser.total = this.events_list[i].amount;
              }
            }
            this.fee.users.push(feeuser);
          }

          // finance
          if(this.events_list[i].amount || this.events_list[i].expense) {
            if(this.events_list[i].expense) {
              // completed
              this.fee.finance.total += parseInt(this.events_list[i].expense.finalprice);
              this.fee.finance.expenditure += parseInt(this.events_list[i].expense.expenditure);
              this.fee.finance.zctingche += parseInt(this.events_list[i].expense.zctingche);
              this.fee.finance.zccanyin += parseInt(this.events_list[i].expense.zccanyin);
              this.fee.finance.zcgaosu += parseInt(this.events_list[i].expense.zcgaosu);
              this.fee.finance.zcjiayou += parseInt(this.events_list[i].expense.zcjiayou);
              this.fee.finance.zcmaihuo += parseInt(this.events_list[i].expense.zcmaihuo);
              this.fee.finance.zcother += parseInt(this.events_list[i].expense.zcother);
              this.fee.finance.fxrmb += parseInt(this.events_list[i].expense.fxrmb);
              this.fee.finance.fxjpy += parseInt(this.events_list[i].expense.fxjpy);
            } else {
              // undone
              if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
                this.fee.finance.total += parseInt(this.events_list[i].amount);
                this.fee.finance.undone += parseInt(this.events_list[i].amount);
              }
            }
          }
        }
      }
    },
    // type 1 未完成 2 全部
    // eventswitch 1 普通任务 2 見積
    setEvents(type = 1) {
      // auto set showCompleted flag
      if(type == 1) {
        this.showCompleted = false;
      } else {
        this.showCompleted = true;
      }
      this.eventDataSources.estimateundone = [];
      this.eventDataSources.estimatecompleted = [];
      this.eventDataSources.ordinaryundone = [];
      this.eventDataSources.ordinarycompleted = [];
      // this.events = [];
      var view = $('#calendartodo').fullCalendar('getView');
      for(var i in this.events_list) {
        var eventObj = new Object();
        var amount = "";
        if(this.events_list[i].amount) {
          amount = this.$parent.$options.methods.formatNumberJPY(this.events_list[i].amount);
        }
        var apm = ' ';
        eventObj.id = this.events_list[i].id;
        eventObj.start = this.events_list[i].event_date;
        switch(+this.events_list[i].apm) {
          case 1:
            eventObj.start += 'T08:00:00';
            eventObj.end = this.events_list[i].event_date + 'T12:00:00';
            apm += this.$i18n.t('event.morning');
            break;
          case 2:
            eventObj.start += 'T12:00:00';
            eventObj.end = this.events_list[i].event_date + 'T18:00:00';
            apm += this.$i18n.t('event.afternoon');
            break;
          case 3:
            eventObj.start += 'T18:00:00';
            eventObj.end = this.events_list[i].event_date + 'T21:00:00';
            apm += this.$i18n.t('event.night');
            break;
          case 5:
            if(this.events_list[i].start_time) {
              eventObj.start += 'T' + this.events_list[i].start_time;
            }
            if(this.events_list[i].end_time) {
              eventObj.end = this.events_list[i].event_date + 'T' + this.events_list[i].end_time;
            }
            break;
          default:
            eventObj.allDay = true;
            apm += this.$i18n.t('event.alldayT');
            break;
        }
        
        eventObj.title = amount+apm;
        var truck = '';
        if(this.events_list[i].trucks.length > 0) {
          truck = '<i class="fa fa-truck"></i> '+this.events_list[i].trucks.join(", ")+'<br/>';
        }
        if(eventObj.title == ' ') {
          eventObj.title = '<i class="fa fa-tag"></i> '+this.events_list[i].typenames.join(", ");
          var typenames = '';
        } else {
          var typenames = '<i class="fa fa-tag"></i> '+this.events_list[i].typenames.join(", ")+'<br/>';
        }
        
        if(this.events_list[i].status == 1) {
          // 未完成
          // description well show on agendaDay view only.
          // completed well show on any views.
          var description = '<div class="eventdescription">';
          if(this.events_list[i].carefulnames.length > 0) {
            description += '<i class="fa fa-exclamation-triangle"></i> '+this.events_list[i].carefulnames.join(", ")+'<br/>';
          }
          if(this.events_list[i].totalname) {
            description += '<i class="fa fa-cubes"></i> '+this.events_list[i].totalname+'<br/>';
          }
          if(this.events_list[i].goods.length > 0) {
            description += '<i class="fa fa-cube"></i> '+this.events_list[i].goods.join(", ")+'<br/>';
          }
          if(this.events_list[i].deposit) {
            if(this.events_list[i].deposit.jpy > 0) {
              description += this.$t('event.depositjpy')+this.$parent.$options.methods.formatNumberJPY(this.events_list[i].deposit.jpy)+' ';
            }
            if(this.events_list[i].deposit.rmb > 0) {
              description += this.$t('event.depositrmb')+this.$parent.$options.methods.formatNumberJPY(this.events_list[i].deposit.rmb)+' ';
            }
            if(this.events_list[i].deposit.jpy || this.events_list[i].deposit.rmb) {
              description += '<br/>';
            }
          }
          if(this.events_list[i].details.phone) {
            description += '<i class="fa fa-phone"></i> '+this.events_list[i].details.phone;
          }
          if(this.events_list[i].details.wechat) {
            description += ' <i class="fa fa-wechat"></i> '+this.events_list[i].details.wechat;
          }
          if(this.events_list[i].details.phone || this.events_list[i].details.wechat) {
            description += '<br/>';
          }
          if(this.events_list[i].partner) {
            description += ' <i class="fa fa-male"></i> '+this.events_list[i].partner;
          }
          if(this.events_list[i].product_list_id) {
            description += ' <i class="fa fa-shopping-cart"></i> '+this.events_list[i].product_list_id;
          }
          if(this.events_list[i].order_id) {
            description += ' <i class="fa fa-comments-o"></i> '+this.events_list[i].order_id;
          }
          if(this.events_list[i].partner || this.events_list[i].product_list_id || this.events_list[i].order_id) {
            description += '<br/>';
          }
          if(this.events_list[i].details.from_address) {
            description += 'From: '+this.events_list[i].details.from_address;
            if(this.events_list[i].details.from_btype == 1) {
              description += ' '+this.$t('offer.buildingtype1');
            } else if(this.events_list[i].details.from_btype == 2) {
              description += ' '+this.$t('offer.buildingtype2');
            } else if(this.events_list[i].details.from_btype == 3) {
              description += ' '+this.$t('offer.buildingtype3');
            } else if(this.events_list[i].details.from_btype == 4) {
              description += ' '+this.$t('offer.buildingtype4');
            } else if(this.events_list[i].details.from_btype == 5) {
              description += ' '+this.$t('offer.buildingtype5');
            } else if(this.events_list[i].details.from_btype == 6) {
              description += ' '+this.$t('offer.buildingtype6');
            }
            if(this.events_list[i].details.from_elevator == 1) {
              description += ' '+this.$t('event.elevator');
            } else if(this.events_list[i].details.from_elevator == 2) {
              description += ' '+this.$t('event.stairs');
            } else if(this.events_list[i].details.from_elevator == 3) {
              description += ' '+this.$t('event.elevatorAndstairs');
            }
            if(this.events_list[i].details.from_floor > 0) {
              description += ' '+this.events_list[i].details.from_floor;
              if(this.events_list[i].details.from_floor > 9) {
                description += '+';
              }
              description += ' '+this.$t('event.floor');
            }
            description += '<br/>';
          }
          if(this.events_list[i].details.to_address) {
            description += 'To: '+this.events_list[i].details.to_address;
            if(this.events_list[i].details.to_btype == 1) {
              description += ' '+this.$t('offer.buildingtype1');
            } else if(this.events_list[i].details.to_btype == 2) {
              description += ' '+this.$t('offer.buildingtype2');
            } else if(this.events_list[i].details.to_btype == 3) {
              description += ' '+this.$t('offer.buildingtype3');
            } else if(this.events_list[i].details.to_btype == 4) {
              description += ' '+this.$t('offer.buildingtype4');
            } else if(this.events_list[i].details.to_btype == 5) {
              description += ' '+this.$t('offer.buildingtype5');
            } else if(this.events_list[i].details.to_btype == 6) {
              description += ' '+this.$t('offer.buildingtype6');
            }
            if(this.events_list[i].details.to_elevator == 1) {
              description += ' '+this.$t('event.elevator');
            } else if(this.events_list[i].details.to_elevator == 2) {
              description += ' '+this.$t('event.stairs');
            } else if(this.events_list[i].details.to_elevator == 3) {
              description += ' '+this.$t('event.elevatorAndstairs');
            }
            if(this.events_list[i].details.to_floor > 0) {
              description += ' '+this.events_list[i].details.to_floor;
              if(this.events_list[i].details.to_floor > 9) {
                description += '+';
              }
              description += ' '+this.$t('event.floor');
            }
            description += '<br/>';
          }
          if(this.events_list[i].images.length > 0) {
            description += '<i class="fa fa-image"></i> '+this.events_list[i].images.length+' ';
          }
          if(this.events_list[i].comments.length > 0) {
            description += '<i class="fa fa-comments"></i> '+this.events_list[i].comments.length+' ';
          }
          description += '</div>';
          eventObj.description = truck + '<b>' + typenames + '</b>' + description;
          eventObj.completed = '';
        } else {
          // 完成
          eventObj.completed = '<i class="ace-icon fa fa-check-square-o"></i> ';
          
          if(this.events_list[i].partner && this.events_list[i].expense.fxrmb == 0 && this.events_list[i].expense.fxjpy == 0) {
            eventObj.completed += '<span style="color:#ffff4d">'+ this.$t('event.unpaid') +'</span>';
          } else if(this.events_list[i].expense.finalprice > 0 || this.events_list[i].expense.expenditure > 0) {
            if(this.events_list[i].expense.finalprice > 0) {
              eventObj.completed += this.$t('event.receipt')+':';
              eventObj.completed += this.$parent.$options.methods.formatNumberJPY(this.events_list[i].expense.finalprice);
            }
            if(this.events_list[i].expense.expenditure > 0) {
              eventObj.completed += this.$t('event.expenditure')+':';
              eventObj.completed += this.$parent.$options.methods.formatNumberJPY(this.events_list[i].expense.expenditure);
            }
          } else {
            eventObj.completed += '<b>'+this.$t('finance.completed')+'</b>';
          }
          eventObj.description = truck + typenames;
        }
        
        if (this.events_list[i].types.indexOf('"4"') > -1 || this.events_list[i].status == 2) {
          // 見積もり已完成  正在考虑  || 完成
          eventObj.color = '#A9A9A9';
        } else if(this.events_list[i].types.indexOf('"5"') > -1) {
          // 見積
          eventObj.color = '#d81b60';
        } else if (this.events_list[i].types.indexOf('"19"') > -1) {
          // 休み
          eventObj.title += '('+this.events_list[i].user.name+')';
          eventObj.color = '#001f3f';
        } else if (
            this.events_list[i].types.indexOf('"12"') > -1 ||
            this.events_list[i].types.indexOf('"13"') > -1 ||
            this.events_list[i].types.indexOf('"14"') > -1 ||
            this.events_list[i].types.indexOf('"15"') > -1 ||
            this.events_list[i].types.indexOf('"16"') > -1
          ) {
          // 店内工作(邮寄纸箱)
          eventObj.color = '#66aac9';
          if(this.events_list[i].apm != 4) {
            eventObj.start = this.events_list[i].event_date + 'T21:00:00';
            eventObj.end = this.events_list[i].event_date + 'T23:59:59';
          }
        } else if(this.events_list[i].types.indexOf('"18"') > -1) {
          // 店内工作(客人来店)
          eventObj.color = '#66aac9';
        }
        
        if(eventObj){
          if(this.events_list[i].status == 1) {
            if(this.events_list[i].types.indexOf('"5"') > -1) {
              // 見積
              this.eventDataSources.estimateundone.push(eventObj);
            } else if(this.events_list[i].types.indexOf('"4"') > -1) {
              // 見積完成
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else {
              // 普通任务
              this.eventDataSources.ordinaryundone.push(eventObj);
            }
          } else {
            // 已完成
            if(this.events_list[i].types.indexOf('"5"') > -1) {
              // 見積
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else if(this.events_list[i].types.indexOf('"4"') > -1) {
              // 見積
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else {
              // 普通任务
              this.eventDataSources.ordinarycompleted.push(eventObj);
            }
          }
        }
      }
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
    },
    addedevent(ymd) {
      $('#calendartodo').fullCalendar('changeView', 'agendaDay', ymd);
      this.get_events(ymd.substring(0,7));
      this.showformflag = false;
      this.showCalendarFlag = true;
    },
    editevent(id, date = '') {
      this.eventid = id;
      if(date) {
        this.eventdate = date;
      }
      this.get_productlist();
      this.showEventItem = false;
      this.showformflag = true;
    },
    copyEvent(id) {
      this.copyid = id;
      this.get_productlist();
      this.showEventItem = false;
      this.showformflag = true;
    },
    completedevent(eventdata) {
      var ymd = '';
      for(var index in this.events_list) {
        if(this.events_list[index].id == eventdata.event_id) {
          this.events_list[index].expense = eventdata;
          this.events_list[index].status = 2;
          ymd = this.events_list[index].event_date;
        }
      }
      this.setEvents(2);
      $('#calendartodo').fullCalendar('changeView', 'agendaDay', ymd);
      this.showEventItem = false;
      this.showCalendarFlag = true;
    },
    get_formtypes() {
      this.$http({
        url: '/api/get_types/',
        method: 'GET'
      }).then(res =>  {
        for (var index in res.data.types) {
          var group_id = res.data.types[index].group_id;
          var option = {};
          option.id = res.data.types[index].id;
          option.text = res.data.types[index].name;
          switch (+group_id) {
            case 1:
              this.formoptions.worktype.push(option);
              break;
            case 2:
              this.formoptions.careful.push(option);
              break;
            case 3:
              this.formoptions.total.push(option);
              break;
            case 4:
              this.formoptions.aboutgoods.push(option);
              break;
            case 5:
              this.formoptions.truck.push(option);
              break;
            default:
              break;
          }
        }
      })
    },
    get_productlist() {
      var eid = "";
      if(this.eventid) {
        eid = this.eventid;
      }
      this.formoptions.product_list = [];
      this.$http({
        url: '/api/get_productlist/' + eid,
        method: 'GET'
      }).then(res =>  {
        for (var index in res.data.productlists) {
          var option = {};
          option.id = res.data.productlists[index].id;
          option.text = '#'+res.data.productlists[index].id+' '+this.$parent.$options.methods.formatNumberJPY(res.data.productlists[index].price);
          this.formoptions.product_list.push(option);
        }
      })
    },
    deleteevent(eventid) {
      this.$http.delete('/admin/event/'+eventid).then(response => {
        if(response) {
          for(var key in this.events_list) {
            if(this.events_list[key].id == eventid){
              this.events_list.splice(key, 1);
            }
          }
          this.setEvents()
          this.showCalendar()
        }
      })
    },
    showCalendar(){
      this.showEventItem = false;
      this.showCalendarFlag = true;
    },
    quickformswitch(showorhide, date = '') {
      if(showorhide) {
        if(date) {
          this.eventdate = date
        } else {
          var view = $('#calendartodo').fullCalendar('getView');
          if(view.name == 'agendaDay') {
            this.eventdate = $('#calendartodo').fullCalendar('getDate').format();
          } else {
            this.eventdate = ""
          }
        }
      }
      if(showorhide) {
        //open
        this.eventid = 0;
        this.showEventItem = false;
      } else {
        //close
        this.checkCountShowList();
      }
      this.showformflag = showorhide;
    }
  },
  filters: {
    formatTime(time, date) {
      return moment(date+' '+time).format('HH:mm');
    },
    formatNumberJPY(number) {
      if(number) {
        return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
      } else {
        return '0';
      }
    },
    truncate: function (value, length) {
      if (!value) return ''
      var length = length ? parseInt(length, 10) : 20
      if(value.length <= length) {
        return value
      }
      else {
        return '...' + value.substring(value.length - length)
      }
    },
    toBiggerImg(filename) {
      return filename.replace("_thumb", "");
    }
  },
  computed: {
    orderedFeeUsers: function () {
        return _.orderBy(this.fee.users, 'total', 'desc')
    }
  }
}

Vue.component('todo', {
  template: '<div></div>',
  props: ['events', 'showCompleted', 'switchEstimates', 'showYmd', 'holidays'],
  data () {
    return {
      cal: null,
      showmonth : ""
    }
  },
  mounted () {
    var self = this
    this.cal = $(self.$el)

    var args = {
      defaultView: 'agendaDay',
      events: self.events.ordinaryundone,
      defaultDate: moment().format('YYYY-MM-DD'),
      minTime: '08:00:00',
      maxTime: '23:00:00',
      height: 1000,
      contentHeight: 1000,
      header: {
        left: 'title createEventButton reloadEventsButton',
        right: 'month agendaWeek agendaDay today tomorrowButton prev,next'
      },
      // 日付クリックイベント
      // dayClick: function(date, jsEvent, view) {
      //   self.gotoDate(date.format());
      // },
      navLinks: true,
      navLinkDayClick: function(date, jsEvent) {
        self.gotoDate(date.format());
        // self.$emit('showform', true, date.format());
      },
      // イベントクリック
      eventClick: function(calEvent, jsEvent, view) {
        self.$emit('showitem', calEvent.id);
      },
      viewRender: function(view, element){
        var showdate = $('#calendartodo').fullCalendar('getDate');
        var formatdate = showdate.format('YYYY-MM');
        if(!self.showmonth || self.showmonth != formatdate) {
          self.$emit('update-events', formatdate);
          self.$emit('updateym', formatdate);
          self.showmonth = formatdate;
        }
        if(showdate.format('YYYY-MM-DD') == moment().add(1, 'd').format('YYYY-MM-DD')) {
          $(".fc-tomorrowButton-button").prop("disabled", true);
          $(".fc-tomorrowButton-button").addClass('fc-state-disabled');
        } else {
          $(".fc-tomorrowButton-button").prop("disabled", false);
          $(".fc-tomorrowButton-button").removeClass('fc-state-disabled');
        }
        if(view.name == 'agendaDay') {
          $(".eventdescription").css('display', 'block');
        } else {
          $(".eventdescription").css('display', 'none');
        }
        self.resetHolidays();
        // $(".eventdescription").removeClass('showdescription');
      },
      eventRender: function(event, element) {
        self.showdescription(event, element);
      },
      customButtons: {
        createEventButton: {
          text: this.$i18n.t('event.addEvent'),
          click: function() {
            self.$emit('showform', true);
          }
        },
        reloadEventsButton: {
          text: this.$i18n.t('global.reload'),
          click: function() {
            self.$emit('reload');
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
    
    this.cal.fullCalendar(args)
    .on('click', '.fc-month-button', function() {
        self.resetFirstDay();
    })
    .on('click', '.fc-agendaWeek-button', function(){
        self.setFirstDay();
    });
  },
  watch: {
    getevents: function(val) {
      this.cal.fullCalendar('removeEventSources');
      this.cal.fullCalendar('addEventSource', val);
      
      var view = this.cal.fullCalendar('getView');
      if(view.name == 'agendaDay') {
        $(".eventdescription").css('display', 'block');
      } else {
        $(".eventdescription").css('display', 'none');
      }
    },
    showYmd(val) {
      this.cal.fullCalendar('gotoDate', this.showYmd);
    },
    holidays() {
      this.resetHolidays();
    }
  },
  methods: {
    gotoDate(date = '') {
      if(date) {
        $(this.$el).fullCalendar('changeView', 'agendaDay', date);
      } else {
        $(this.$el).fullCalendar('changeView', 'agendaDay', moment().add(1, 'd'));
      }
    },
    setFirstDay() {
      $(this.$el).fullCalendar('option', 'firstDay', moment().subtract(1, 'd').day());
    },
    resetHolidays() {
      for(var i in this.holidays) {
        $('.fc-day[data-date="'+this.holidays[i].date+'"]').css('background-color', '#ffe6f0');
      }
    },
    resetFirstDay() {
      $(this.$el).fullCalendar('option', 'firstDay', 1);
    },
    showdescription(event, element) {
      var title = element.find( '.fc-title' );
      title.html( title.text() );
      title.append("<br/>" + event.description);
      if(event.completed) {
        title.append(event.completed);
      }
    }
  },
  computed: {
    getevents: function() {
      if(this.switchEstimates) {
        //日程
        if(this.showCompleted) {
          return this.events.ordinarycompleted.concat(this.events.ordinaryundone).concat(this.events.estimateundone).concat(this.events.estimatecompleted);
        } else {
          return this.events.ordinaryundone.concat(this.events.estimateundone);
        }
      } else {
        //見積
        return this.events.estimatecompleted.concat(this.events.estimateundone);
      }
    }
  }
})
</script>
<style scoped>
.switch input, .switchestimates input {
    display: none;
}
.switch label, .switchestimates label {
    padding: 6px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}
.switch label:before, .switchestimates label:before {
    padding: 6px 10px;
    content: '显示';
    border-radius: 6px 0 0 6px;
    background: linear-gradient(to bottom, #F0F0F0 0%, #DDD 100%);
    box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.1) inset;
    color: #333;
}
.switchestimates label:before {
  content: '日程';
}
.switch label:after, .switchestimates label:after {
    padding: 6px 10px;
    content: '隐藏';
    border-radius: 0 6px 6px 0;
    background: #d81b60;
    box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.1) inset;
    color: #FFF;
}
.switchestimates label:after {
  content: '見積';
}
.switch input + label:hover:before, .switchestimates input + label:hover:before {
    opacity: 0.5;
}
.switch input:checked + label:before, .switchestimates input:checked + label:before {
    background: #3a87ad;
    color: #FFF;
    opacity: 1;
}
.switch input:checked + label:after, .switchestimates input:checked + label:after {
    background: linear-gradient(to bottom, #F0F0F0 0%, #DDD 100%);
    color: #333;
}
.switch input:checked + label:hover:after, .switchestimates input:checked + label:hover:after {
    opacity: 0.5;
}
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
