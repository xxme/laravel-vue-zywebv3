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
          <div class="navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="dropdown">
                <button href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" role="button"><i class="fa fa-list"></i> {{ $t('topmenu.quicknav') }}<span class="caret"></span></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a @click="$router.push('/admin/estimate')"><i class="fa fa-comments-o"></i> {{ $t('nav.estimateslist') }}</a></li>
                  <li><a @click="$router.push('/admin/productlist')"><i class="fa fa-shopping-cart"></i> {{ $t('nav.shoppinglist') }}</a></li>
                  <li><a v-if="auth && auth.group_id == 1" @click="$router.push('/admin/finances')"><i class="fa fa-money"></i> {{ $t('nav.money') }}</a></li>
                  <li><a v-if="auth && auth.group_id == 1" v-show="showCalendarFlag" @click="showstatistics(true)"><i class="fa fa-usd"></i> {{ $t('nav.statistics') }}</a></li>
                </ul>
              </li>
            </ul>
            <span v-show="!showEventItem && !showformflag" class="pull-right">
              <div class="switchestimates">
                <input id="switchestimates" v-model="switchEstimates" type="checkbox">
                <label for="switchestimates"></label>
              </div>
            </span>
          </div>
        </div>
        <div class="nav-tabs-custom" v-show="!showformflag && !showStatisticsFlag && showCalendarFlag">
          <div class="tab-content">
            <!-- /.tab-pane -->
            <div class="active tab-pane" id="topcalendar">
              <todo :events="eventDataSources" :showCompleted="showCompleted" :switchEstimates="switchEstimates" :showYmd="showYmd" :holidays="holidays" @updateym="updateym" @update-events="get_events" @showform="quickformswitch" @showstatistics="showstatistics(true)" @showatte="atteformswitch(true)" @showitem="showitem" @reload="reloadEvents" id="calendartodo"></todo>
              
              <div class="box-body col-xs-12" style="float:none">
                <div class="form-group col-xs-6" style="line-height: 35px;">
                  <label for="inputSearch" class="col-sm-2 control-label">{{ $t('event.search') }}</label>
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id="inputSearch" v-model="keyword" placeholder="Key word">
                  </div>
                </div>
                <div class="form-group col-xs-6">
                  <div class="col-sm-10">
                    <div class="col-sm-5">
                      <input type="text" id="searchFrom" v-model="searchFrom" />
                    </div>
                    <div class="col-sm-1"> 
                      〜
                    </div> 
                    <div class="col-sm-5">
                      <input type="text" id="searchTo" v-model="searchTo" />
                    </div>
                  </div>
                  <span class="input-group-btn col-sm-2">
                    <button type="button" class="btn btn-default" @click="clearSearch">{{ $t('event.clearSearch') }}</button>
                    <button type="button" class="btn btn-info" @click="searchGo">{{ $t('event.search') }}</button>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- /.tab-content -->
        </div>
        <event-item :eventdata="show_list" :auth="auth" :userlist="user_list" :showflag="showEventItem" :showCompleted="showCompleted" @editevent="editevent" @showCalendar="showCalendar" @completedevent="completedevent" @deleteevent="deleteevent" @copyevent="copyEvent"></event-item>
        <quick-form v-if="showformflag" :formoptions="formoptions" :eventdate="eventdate" :eventid="eventid" :copyid="copyid" @closeform="quickformswitch(false)" @addedevent="addedevent"></quick-form>
        <attendance v-if="showAtteFlag" :userlist="user_list" @showform="atteformswitch(true)" @closeform="atteformswitch(false)"></attendance>
        <statistics v-if="showStatisticsFlag" :fee="fee" :showYm="showYm" :LastYearSales="LastYearSales" :ThisYearSales="ThisYearSales" @closeform="showstatistics(false)"></statistics>
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
import Attendance from './Attendance.vue';
import EventItem from './EventItem.vue';
import Statistics from './Statistics.vue'
import datetimepicker from 'jquery-datetimepicker'

export default {
  mounted() {
    this.get_formtypes()
    this.setDatePicker()
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
      showAtteFlag: false,
      showStatisticsFlag: false,
      loadingShow: false,
      showCalendarFlag: true,
      switchEstimates: true,
      showYmd: moment().format('YYYY-MM-DD'),
      showYm: moment().format('YYYY年MM月'),
      searchFrom: "",
      searchTo: "",
      keyword: "",
      LastYearSales: [],
      ThisYearSales: [],
      formoptions: {
        worktype: [],
        careful: [],
        total: [],
        aboutgoods: [],
        truck: [],
        expenditure: [],
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
    Attendance,
    QuickForm,
    Statistics
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
          this.setFee(ym);
          if (this.auth && this.auth.group_id != 1) {
            $('.fc-statisticsButton-button').hide();
          }
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
    setFee(ym) {
      this.resetFee();
      this.$http({
          url: '/admin/income/' + ym,
          method: 'GET'
      }).then(res =>  {
        for (var index in res.data) {
          // 报价
          var amount = parseInt(res.data[index].amount);
          // 定金
          var jpy = parseInt(res.data[index].jpy);
          // 最后收款
          var finalprice = parseInt(res.data[index].finalprice);
          if (finalprice == 0) {
            // 未完成或没有最终收钱的日程
            if (amount > jpy) {
              this.fee.finance.total += amount;
              this.fee.finance.undone += amount - jpy;
            } else {
              this.fee.finance.total += jpy;
            }
          } else {
            this.fee.finance.total += finalprice + jpy;
          }
        }
      })
      // for(var i in this.events_list) {
      //   if(this.events_list[i].amount || this.events_list[i].expense || (this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0)) {
      //     var hasuserdata = false;
      //     for(var index in this.fee.users) {
      //       if(this.fee.users[index].id == this.events_list[i].user.id) {
      //         hasuserdata = true;
      //         if(this.events_list[i].expense) {
      //           // completed
      //           this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].expense.finalprice);
      //           this.fee.users[index].completed = parseInt(this.fee.users[index].completed) + parseInt(this.events_list[i].expense.finalprice);
      //           if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //             // 定金
      //             this.fee.users[index].completed = parseInt(this.fee.users[index].completed) + parseInt(this.events_list[i].deposit.jpy);
      //             this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].deposit.jpy);
      //           }
      //         } else {
      //           // undone
      //           if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
      //             if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //               // 定金
      //               this.fee.users[index].completed = parseInt(this.fee.users[index].completed) + parseInt(this.events_list[i].deposit.jpy);
      //               this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].amount) - parseInt(this.events_list[i].deposit.jpy);
      //             } else {
      //               this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].amount);
      //             }
      //           }
      //         }
      //       }
      //     }
      //     if(!hasuserdata && (this.events_list[i].amount || this.events_list[i].expense)) {
      //       var feeuser = new Object();
      //       feeuser.id = this.events_list[i].user.id;
      //       feeuser.name = this.events_list[i].user.name;
      //       feeuser.total = 0;
      //       feeuser.completed = 0;
      //       if(this.events_list[i].user.profileimg) {
      //         feeuser.profileimg = this.events_list[i].user.profileimg;
      //       }
            
      //       if(this.events_list[i].expense) {
      //         // completed
      //         feeuser.total = parseInt(feeuser.total) + parseInt(this.events_list[i].expense.finalprice);
      //         feeuser.completed = parseInt(feeuser.completed) + parseInt(this.events_list[i].expense.finalprice);
      //         if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //           // 定金
      //           feeuser.completed = parseInt(feeuser.completed) + parseInt(this.events_list[i].deposit.jpy);
      //           feeuser.total = parseInt(feeuser.total) + parseInt(this.events_list[i].deposit.jpy);
      //         }
      //       } else {
      //         // undone
      //         if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
      //           if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //             // 定金
      //             feeuser.completed = parseInt(feeuser.completed) + parseInt(this.events_list[i].deposit.jpy);
      //             feeuser.total = parseInt(feeuser.total) + parseInt(this.events_list[i].amount) - parseInt(this.events_list[i].deposit.jpy);
      //           } else {
      //             feeuser.total = parseInt(feeuser.total) + parseInt(this.events_list[i].amount);
      //           }
      //         }
      //       }
      //       this.fee.users.push(feeuser);
      //     }

      //     // finance
      //     if(this.events_list[i].amount || this.events_list[i].expense) {
      //       if(this.events_list[i].expense) {
      //         // completed
      //         this.fee.finance.total += parseInt(this.events_list[i].expense.finalprice);
      //         if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //           this.fee.finance.total += parseInt(this.events_list[i].deposit.jpy);
      //         }
      //         this.fee.finance.expenditure += parseInt(this.events_list[i].expense.expenditure);
      //         this.fee.finance.zctingche += parseInt(this.events_list[i].expense.zctingche);
      //         this.fee.finance.zccanyin += parseInt(this.events_list[i].expense.zccanyin);
      //         this.fee.finance.zcgaosu += parseInt(this.events_list[i].expense.zcgaosu);
      //         this.fee.finance.zcjiayou += parseInt(this.events_list[i].expense.zcjiayou);
      //         this.fee.finance.zcmaihuo += parseInt(this.events_list[i].expense.zcmaihuo);
      //         this.fee.finance.zcother += parseInt(this.events_list[i].expense.zcother);
      //         this.fee.finance.fxrmb += parseInt(this.events_list[i].expense.fxrmb);
      //         this.fee.finance.fxjpy += parseInt(this.events_list[i].expense.fxjpy);
      //       } else {
      //         // undone
      //         if(this.events_list[i].types.indexOf('"5"') === -1 && this.events_list[i].types.indexOf('"4"') === -1) {
      //           if(this.events_list[i].deposit && this.events_list[i].deposit.jpy > 0) {
      //             // this.fee.finance.total += parseInt(this.events_list[i].amount) - parseInt(this.events_list[i].deposit.jpy);
      //             this.fee.finance.undone += parseInt(this.events_list[i].amount) - parseInt(this.events_list[i].deposit.jpy);
      //           } else {
      //             // this.fee.finance.total += parseInt(this.events_list[i].amount);
      //             this.fee.finance.undone += parseInt(this.events_list[i].amount);
      //           }
      //         }
      //       }
      //     }
      //   }
      // }
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
        
        if(this.events_list[i].status == 1 || this.events_list[i].status == 3) {
          // 未完成及支出任务
          // description well show on agendaDay view only.
          // completed well show on any views.
          var description = '<div class="eventdescription">';
          if(this.events_list[i].carefulnames.length > 0) {
            description += '<i class="fa fa-exclamation-triangle"></i> '+this.events_list[i].carefulnames.join(", ")+'<br/>';
          }
          if(this.events_list[i].totalname.length > 0) {
            description += '<i class="fa fa-cubes"></i> '+this.events_list[i].totalname.join(" <i class='fa fa-plus'></i> ")+'<br/>';
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
          
          if (this.events_list[i].expense == null) {
            // 未知错误
            console.log(this.events_list[i]);
          } else if(this.events_list[i].partner && this.events_list[i].expense.fxrmb == 0 && this.events_list[i].expense.fxjpy == 0) {
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
        
        if (this.events_list[i].status == 3) {
          // 支出
          eventObj.color = '#d81b60';
        } else if (this.events_list[i].types.indexOf('"4"') > -1 || this.events_list[i].status == 2) {
          // 見積もり已完成  正在考虑  || 完成
          eventObj.color = '#A9A9A9';
        } else if(this.events_list[i].types.indexOf('"5"') > -1) {
          // 見積
          eventObj.color = '#f39c12';
        } else if (this.events_list[i].types.indexOf('"19"') > -1) {
          // 休み
          eventObj.title += '('+this.events_list[i].user.name+')';
          eventObj.color = '#009900';
        // } else if (this.events_list[i].types.indexOf('"122"') > -1) {
        //   // 出勤
        //   eventObj.title += '('+this.events_list[i].user.name+')';
        //   eventObj.color = '#009900';
        // } else if (
        //     this.events_list[i].types.indexOf('"12"') > -1 ||
        //     this.events_list[i].types.indexOf('"13"') > -1 ||
        //     this.events_list[i].types.indexOf('"14"') > -1 ||
        //     this.events_list[i].types.indexOf('"15"') > -1 ||
        //     this.events_list[i].types.indexOf('"16"') > -1
        //   ) {
        //   // 店内工作(邮寄纸箱)
        //   eventObj.color = '#66aac9';
        //   if(this.events_list[i].apm != 4) {
        //     eventObj.start = this.events_list[i].event_date + 'T21:00:00';
        //     eventObj.end = this.events_list[i].event_date + 'T23:59:59';
        //   }
        } else if(this.events_list[i].types.indexOf('"18"') > -1) {
          // 店内工作(客人来店)
          eventObj.color = '#66aac9';
        }
        
        if(eventObj){
          if(this.events_list[i].status == 1 || this.events_list[i].status == 3) {
            // 以下为未完成的各种任务
            if(this.events_list[i].types.indexOf('"5"') > -1) {
              // 見積
              this.eventDataSources.estimateundone.push(eventObj);
            } else if(this.events_list[i].types.indexOf('"4"') > -1) {
              // 見積完成考虑中
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else {
              // 普通任务
              this.eventDataSources.ordinaryundone.push(eventObj);
            }
          } else {
            // 以下为已完成的各种任务
            if(this.events_list[i].types.indexOf('"5"') > -1) {
              // 見積
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else if(this.events_list[i].types.indexOf('"4"') > -1) {
              // 見積完成考虑中
              this.eventDataSources.estimatecompleted.push(eventObj);
            } else {
              // 普通任务
              this.eventDataSources.ordinarycompleted.push(eventObj);
            }
          }
        }
      }
    },
    // percentage(usertotal) {
    //     return Math.round(usertotal / this.fee.finance.total * 100) + '%';
    // },
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
      this.copyid = 0;
      this.eventid = id;
      if(date) {
        this.eventdate = date;
      }
      this.get_productlist();
      this.showEventItem = false;
      this.showformflag = true;
    },
    copyEvent(id) {
      this.eventid = 0;
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
        url: '/api/get_types',
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
            case 6:
              this.formoptions.expenditure.push(option);
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
      this.show_list = [];
      this.showCalendarFlag = true;
    },
    atteformswitch(showorhide) {
      this.showCalendarFlag = !showorhide;
      this.showAtteFlag = showorhide;
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
    },
    showstatistics(showorhide) {
      if(showorhide && this.LastYearSales.length == 0) {
        this.loadingShow = true;
        this.$http({
          url: '/admin/events/getsales/1',
          method: 'GET'
        }).then(res =>  {
          for (var i = 1; i < 13; i++) {
            this.LastYearSales.push(res.data.lastyear[i]);
            this.ThisYearSales.push(res.data.thisyear[i]);
          }
          this.loadingShow = false;
          this.showStatisticsFlag = showorhide;
        })
      } else {
        this.showStatisticsFlag = showorhide;
      }
    },
    setDatePicker() {
      const self = this
 
      $.datetimepicker.setLocale('zh')
      $('#searchFrom').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('#searchTo').datetimepicker({dayOfWeekStart: 1, timepicker:false, format:'Y-m-d'})
      $('#searchFrom').on('change', (e) => {
        if($(e.target).val() != ''){
          self.searchFrom = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.searchFrom = '';
        }
      })
      $('#searchTo').on('change', (e) => {
        if($(e.target).val() != ''){
          self.searchTo = moment($(e.target).val()).format('YYYY-MM-DD')
        } else {
          self.searchTo = '';
        }
      })
    },
    clearSearch() {
      var message = this.$t('event.clearSearchConfirm');
      if(window.confirm(message)) {
        this.searchFrom = "";
        this.searchTo = "";
        this.keyword = "";
        // this.reloadEvents();
      }
    },
    searchGo() {
      if (!this.keyword || !this.searchFrom) {
        alert(this.$i18n.t('event.noKeyOrFrom'));
      } else {
        this.loadingShow = true;
        var formData = new FormData();
        formData.append("keyword", this.keyword);
        formData.append("from", this.searchFrom);
        formData.append("to", this.searchTo);
        this.$http.post('/admin/event/search', formData).then(response => {
          if (response.data.events.length > 0) {
            var responsedata = response.data;
            var filterdata = [];
            for(var index in response.data.events) {
              var eventid = "";
              for(var cf in response.data.events[index].carefulnames) {
                if (eventid == "" && response.data.events[index].carefulnames[cf].indexOf(this.keyword) !== -1) {
                  filterdata.push(response.data.events[index]);
                  eventid = response.data.events[index].id;
                }
              }
              if (eventid == "") {
                for(var cm in response.data.events[index].comments) {
                  if (eventid == "" && response.data.events[index].comments[cm].content.indexOf(this.keyword) !== -1) {
                    filterdata.push(response.data.events[index]);
                    eventid = response.data.events[index].id;
                  }
                }
              }

              if (eventid == "") {
                if (this.keyword == response.data.events[index].id
                    || (response.data.events[index].details.from_address &&
                    response.data.events[index].details.from_address.indexOf(this.keyword) !== -1)
                    || (response.data.events[index].details.to_address && 
                    response.data.events[index].details.to_address.indexOf(this.keyword) !== -1)
                    || (response.data.events[index].details.phone &&
                    response.data.events[index].details.phone.indexOf(this.keyword) !== -1)
                    || (response.data.events[index].details.wechat &&
                    response.data.events[index].details.wechat.indexOf(this.keyword) !== -1)
                    || (response.data.events[index].partner &&
                    response.data.events[index].partner.indexOf(this.keyword) !== -1)) {
                  filterdata.push(response.data.events[index]);
                  eventid = response.data.events[index].id;
                }
              }
              if (eventid == "") {
                for(var gd in response.data.events[index].goods) {
                  if (eventid == "" && response.data.events[index].goods[gd].indexOf(this.keyword) !== -1) {
                    filterdata.push(response.data.events[index]);
                    eventid = response.data.events[index].id;
                  }
                }
              }
              if (eventid == "") {
                for(var tt in response.data.events[index].totalname) {
                  if (eventid == "" && response.data.events[index].totalname[tt].indexOf(this.keyword) !== -1) {
                    filterdata.push(response.data.events[index]);
                    eventid = response.data.events[index].id;
                  }
                }
              }
              if (eventid == "") {
                for(var tn in response.data.events[index].typenames) {
                  if (eventid == "" && response.data.events[index].typenames[tn].indexOf(this.keyword) !== -1) {
                    filterdata.push(response.data.events[index]);
                    eventid = response.data.events[index].id;
                  }
                }
              }
              if (eventid == "") {
                if (response.data.events[index].user.name.indexOf(this.keyword) !== -1) {
                  filterdata.push(response.data.events[index]);
                  eventid = response.data.events[index].id;
                }
              }
              if (eventid == "") {
                if (response.data.events[index].amount == this.keyword) {
                  filterdata.push(response.data.events[index]);
                  eventid = response.data.events[index].id;
                }
              }
            }
            if (filterdata.length > 0) {
              this.show_list = filterdata;
              this.checkCountShowList();
              window.scrollTo(0,0);
            } else {
              alert(this.$i18n.t('event.noDataFind'));
            }
          } else {
            alert(this.$i18n.t('event.noDataFind'));
          }

          this.loadingShow = false;
        })
      }
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
  // },
  // computed: {
  //   orderedFeeUsers: function () {
  //       return _.orderBy(this.fee.users, 'total', 'desc')
  //   }
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
      minTime: '06:00:00',
      maxTime: '24:00:00',
      height: 1000,
      contentHeight: 1000,
      header: {
        left: 'title createEventButton batchEventButton reloadEventsButton',
        right: 'month agendaDay today prev,next'
      },
      // 日付クリックイベント
      // dayClick: function(date, jsEvent, view) {
      //   self.gotoDate(date.format());
      // },
      navLinks: true,
      navLinkDayClick: function(date, jsEvent) {
        self.gotoDate(date.format());
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
        if(view.name == 'agendaDay') {
          $(".eventdescription").css('display', 'block');
        } else {
          $(".eventdescription").css('display', 'none');
        }
        self.resetHolidays();
      },
      eventRender: function(event, element) {
        self.showdescription(event, element);
      },
      customButtons: {
        createEventButton: {
          icon: 'fa fa fa-calendar-plus-o',
          click: function() {
            self.$emit('showform', true);
          }
        },
        batchEventButton: {
          icon: 'fa fa fa-calendar',
          click: function() {
            self.$emit('showatte', true);
          }
        },
        reloadEventsButton: {
          icon: 'fa fa fa-refresh',
          click: function() {
            self.$emit('reload');
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
    },
    statisticsSwitch(active) {
      if (active) {
        $('#topcalendar').removeClass('active');
        $('#statistics').addClass('active');
      } else {
        $('#statistics').removeClass('active');
        $('#topcalendar').addClass('active');
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
.navbar-collapse {
  padding: 0;
}
.dropdown-menu i {
  width: 30px;
}
.navbar-nav {
  float: left;
  margin: 0;
}
.navbar-nav .open .dropdown-menu {
  position: absolute;
  background-color: #FFF;
}
</style>
