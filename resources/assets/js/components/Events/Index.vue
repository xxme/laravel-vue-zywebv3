<template>
  <div>
    <div>
      <section class="content-header">
        <h1>
          {{ $t('event.pagetitle') }}
          <span class="pull-right">
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
        <universe-nav :showYmd="showYmd" :events="events" :holidays="holidays" :fee="fee" :auth="auth" v-on:update-events="get_events" @showform="createEvent" @showdata="showEvent" @showitem="showitem"></universe-nav>
        <event-item :eventdata="show_list" :auth="auth"></event-item>
      </section>
    </div>
  </div>
</template>

<script>
import moment from 'moment'
// import baguetteBox from 'baguettebox.js'
import UniverseNav from '../Public/UniverseNav'
// import Select2 from './Select2'
// import QuickForm from './QuickForm.vue';
import EventItem from './EventItem.vue';

export default {
  data() {
    return {
      auth: null,
      events_list: [],
      user_list: [],
      show_list: [],
      events: [],
      eventdate: "",
      eventid: 0,
      holidays: [],
      showCompleteModal: false,
      showCompleted: false,
      showQuickForm: false,
      showYmd: moment().format('YYYY-MM-DD'),
      completeinfo: {
        eventid: "",
        eventkey: "",
        haspartner: false,
        price: 0,
        expenditure: 0,
        tingche: 0,
        canyin: 0,
        gaosu: 0,
        jiayou: 0,
        maihuo: 0,
        other: 0,
        rmb: 0,
        jpy: 0,
        sta: 1,
        payee: ""
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
  // updated: function () {
  //   this.$nextTick(function () {
  //     baguetteBox.run('.gallery', {
  //       noScrollbars: true
  //     });
  //     if(!this.showQuickForm && this.eventid) {
  //       var pos = $("#event"+this.eventid).offset().top;
  //       $(window).scrollTop(pos);
  //       this.eventid = 0;
  //     } else if(!this.showQuickForm && this.eventdate) {
  //       var pos = $("#date"+this.eventdate).offset().top;
  //       $(window).scrollTop(pos);
  //       this.eventdate = "";
  //     }
  //   })
  // },
  components: {
    // Select2,
    EventItem,
    UniverseNav
    // QuickForm
  },
  methods: {
    get_events(ym, ymd = "") {
      this.events_list = [];
      $('#eventrs').html(this.$t('global.loading'));
      this.$http({
          url: '/admin/events/' + ym,
          method: 'GET'
      }).then(res =>  {
          this.holidays = res.data.holidays;
          // this.resetFee();
          if(res.data.events.length == 0) {
            $('#eventrs').html(this.$t('global.noRes'));
          } else {
            this.events_list = res.data.events;
            this.setEvents();
            this.auth = res.data.auth;
            for (var index in res.data.users) {
              var user = {};
              user.id = res.data.users[index].id;
              user.text = res.data.users[index].name;
              this.user_list.push(user);
            }
            // jump ymd
            // if(ymd) {
            //   var pos = $("#date"+ymd).offset().top;
            //   $(window).scrollTop(pos);
            // }
          }
      })
    },
    showEvent(date) {
      this.show_list = [];
      for(var index in this.events_list) {
        if(this.events_list[index].event_date == date) {
          this.show_list.push(this.events_list[index]);
        }
      }
    },
    showitem(id) {
      this.show_list = [];
      for(var index in this.events_list) {
        if(this.events_list[index].id == id) {
          this.show_list.push(this.events_list[index]);
        }
      }
    },
    // resetFee() {
    //   this.fee.users = [];
    //   this.fee.finance.total = 0;
    //   this.fee.finance.undone = 0;
    //   this.fee.finance.expenditure = 0;
    //   this.fee.finance.zctingche = 0;
    //   this.fee.finance.zccanyin = 0;
    //   this.fee.finance.zcgaosu = 0;
    //   this.fee.finance.zcjiayou = 0;
    //   this.fee.finance.zcmaihuo = 0;
    //   this.fee.finance.zcother = 0;
    //   this.fee.finance.fxrmb = 0;
    //   this.fee.finance.fxjpy = 0;
    // },
    createEvent(date = '') {
      this.eventid = 0;
      this.eventdate = date;
      this.showQuickForm = true;
    },
    // editEvent(eventid) {
    //   this.eventid = eventid;
    //   this.showQuickForm = true;
    // },
    // updateEvent(event) {
    //   this.showQuickForm = false;
    //   for(var index in this.events_list) {
    //     if(event.id == this.events_list[index].id) {
    //       this.events_list[index] = event;
    //     }
    //   }
    // },
    // addEvent(ym) {
    //   this.showQuickForm = false;
    //   this.showYmd = moment(ym + '-01').format('YYYY-MM-DD');
    //   this.get_events(ym);
    // },
    setEvents(type = 1) {
      // type 1 未完成 2 全部
      this.events = [];
      for(var i = 0; i < this.events_list.length; i++) {
        var eventObj = new Object();
        var amount = "";
        if(this.events_list[i].amount) {
          amount = this.$parent.$options.methods.formatNumberJPY(this.events_list[i].amount);
        }
        var apm = '\n';
        eventObj.id = this.events_list[i].id;
        eventObj.start = this.events_list[i].event_date;
        switch(+this.events_list[i].apm) {
          case 1:
            eventObj.start += 'T08:00:00';
            eventObj.end = this.events_list[i].event_date + 'T12:00:00';
            apm = ' '+this.$i18n.t('event.morning')+'\n';
            break;
          case 2:
            eventObj.start += 'T12:00:00';
            eventObj.end = this.events_list[i].event_date + 'T18:00:00';
            apm = ' '+this.$i18n.t('event.afternoon')+'\n';
            break;
          case 3:
            eventObj.start += 'T18:00:00';
            eventObj.end = this.events_list[i].event_date + 'T21:00:00';
            apm = ' '+this.$i18n.t('event.night')+'\n';
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
            apm = ' '+this.$i18n.t('event.alldayT')+'\n';
            break;
        }
        
        eventObj.title = amount+apm+this.events_list[i].typenames.join(", ");
        var description = "";
        if(this.events_list[i].details.phone) {
          description += this.events_list[i].details.phone;
        }
        if(this.events_list[i].details.wechat) {
          if(description != "") {
            description += ' ';
          }
          description += this.events_list[i].details.wechat;
        }
        eventObj.description = description;
        if(this.events_list[i].types.indexOf(5) > -1) {
          // 見積
          eventObj.color = '#d81b60';
        } else if (this.events_list[i].types.indexOf(19) > -1) {
          // 休み
          eventObj.title += '('+this.events_list[i].user.name+')';
          eventObj.color = '#001f3f';
        } else if (this.events_list[i].types.indexOf(4) > -1) {
          // 見積もり已完成  正在考虑
          eventObj.color = '#A9A9A9';
        }
        if((type == 1 && this.events_list[i].status == 1) || type == 2) {
          this.events.push(eventObj);
        }

        // nav finance
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
                this.fee.users[index].total = parseInt(this.fee.users[index].total) + parseInt(this.events_list[i].amount);
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
              feeuser.total = this.events_list[i].amount;
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
              this.fee.finance.total += parseInt(this.events_list[i].amount);
              this.fee.finance.undone += parseInt(this.events_list[i].amount);
            }
          }
        }
      }
    },
    // makered(eventid) {
    //   var dom = $(".col-md-12").find("[data-box='box" + eventid + "']");
    //   if(dom.hasClass('makered')) {
    //     dom.removeClass('makered');
    //   } else {
    //     $(".box-body").each(function(i) {
    //       if($(this).hasClass('makered')){
    //         $(this).removeClass('makered');
    //       }
    //     });
    //     dom.addClass('makered');
    //   }
    // },
    // formatDateWithWeekname(date) {
    //   if(this.$i18n.locale == 'cn') {
    //     moment.locale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
    //     var weekname = moment(date).format('星期dddd');
    //     moment.locale('ja', {weekdays: ["日","月","火","水","木","金","土"]});
    //     weekname += moment(date).format('(dddd)');
    //   } else {
    //     moment.locale('ja');
    //     weekname = moment(date).format('dddd');
    //   }
    //   if(this.holidays && this.holidays[date]) {
    //     weekname += ' '+this.holidays[date];
    //   }

    //   return moment(date).format('YYYY年MM月DD日') + ' ' + weekname;
    // },
    // formatNumberJPY(number) {
    //   if(number) {
    //     return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
    //   } else {
    //     return '';
    //   }
    // },
    // getDateColor(date) {
    //   moment.locale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
    //   if(moment(date).format('dddd') == '日' || this.holidays[date]) {
    //     return 1;
    //   } else if(moment(date).format('dddd') == '六') {
    //     return 2;
    //   }
    //   return false;
    // },
    // sendcomment(eventid) {
    //   var val = $('#comment'+eventid).val();
    //   if(val) {
    //     var formData = new FormData();
    //     formData.append("event_id", eventid);
    //     formData.append("content", val);
    //     this.$http.post('/admin/comment', formData).then(response => {
    //       var comment = response.data;
    //       for(var key in this.events_list) {
    //         if(this.events_list[key].id == eventid){
    //           this.events_list[key].comments.push(comment);
    //         }
    //       }
    //       $('#comment'+eventid).val('');
    //     })
    //   }
    // },
    // deleteevent(eventid, listid) {
    //   var message = this.$t('event.delwarning');
    //   if(listid) {
    //     message += this.$t('event.listWellBeDel')
    //   }
    //   if(window.confirm(message)) {
    //     this.$http.delete('/admin/event/'+eventid).then(response => {
    //       if(response) {
    //         for(var key in this.events_list) {
    //           if(this.events_list[key].id == eventid){
    //             this.events_list.splice(key, 1);
    //           }
    //         }
    //       }
    //     })
    //   }
    // },
    // deletecomment(commentid) {
    //   if(window.confirm('Are you sure delete comment?')) {
    //     this.$http.delete('/admin/comment/'+commentid).then(response => {
    //       if(response) {
    //         for(var key in this.events_list) {
    //           for(var k in this.events_list[key].comments) {
    //             if(this.events_list[key].comments[k].id == commentid){
    //               var index = this.events_list[key].comments.indexOf(this.events_list[key].comments[k]);
    //               this.events_list[key].comments.splice(index, 1);
    //             }
    //           }
    //         }
    //       }
    //     })
    //   }
    // },
    // completeEvent(eventid) {
    //   for(var key in this.events_list) {
    //     if(this.events_list[key].id == eventid) {
    //       if(this.events_list[key].expense) {
    //         this.completeinfo.tingche = this.events_list[key].expense.zctingche;
    //         this.completeinfo.canyin = this.events_list[key].expense.zccanyin;
    //         this.completeinfo.gaosu = this.events_list[key].expense.zcgaosu;
    //         this.completeinfo.jiayou = this.events_list[key].expense.zcjiayou;
    //         this.completeinfo.maihuo = this.events_list[key].expense.zcmaihuo;
    //         this.completeinfo.other = this.events_list[key].expense.zcother;
    //         this.completeinfo.rmb = this.events_list[key].expense.fxrmb;
    //         this.completeinfo.jpy = this.events_list[key].expense.fxjpy;
    //         this.completeinfo.payee = this.events_list[key].expense.user_id;
    //         this.completeinfo.sta = this.events_list[key].expense.status;
    //         this.completeinfo.price = this.events_list[key].expense.finalprice;
    //       } else {
    //         this.completeinfo.tingche = 
    //         this.completeinfo.canyin = 
    //         this.completeinfo.gaosu = 
    //         this.completeinfo.jiayou = 
    //         this.completeinfo.maihuo = 
    //         this.completeinfo.other = 
    //         this.completeinfo.rmb = 
    //         this.completeinfo.jpy = 0;
    //         this.completeinfo.payee = "";
    //         this.completeinfo.sta = 1;
    //         if(this.events_list[key].amount) {
    //           this.completeinfo.price = this.events_list[key].amount;
    //         } else {
    //           this.completeinfo.price = 0;
    //         }
    //       }
    //       this.completeinfo.eventid = eventid;
    //       this.completeinfo.eventkey = key;
    //       if(this.events_list[key].partner) {
    //         this.completeinfo.haspartner = true;
    //       } else {
    //         this.completeinfo.haspartner = false;
    //       }
    //     }
    //   }
    //   this.showCompleteModal = true;
    // },
    // completedo() {
    //   if($('#payee').val()) {
    //     this.completeinfo.payee = $('#payee').val();
    //   } else {
    //     this.completeinfo.payee = this.auth.id;
    //   }
    //   if(this.completeinfo.sta == 1) {
    //     var status = this.$t('event.notpayment');
    //   } else {
    //     var status = this.$t('event.paymented');
    //   }
    //   if(this.completeinfo.payee == this.auth.id) {
    //     var payeename = this.$t('event.myself');
    //   } else {
    //     for(var key in this.user_list) {
    //       if(this.user_list[key].id == this.completeinfo.payee) {
    //         var payeename = this.user_list[key].text;
    //       }
    //     }
    //   }
    //   if(this.completeinfo.price > 0) {
    //     var finalprice = this.formatNumberJPY(this.completeinfo.price);
    //   } else {
    //     var finalprice = status = payeename = '';
    //   }
    //   if(window.confirm(this.$t('global.areYouSure') + 
    //       payeename + ' ' + finalprice + status
    //     )) {
    //     this.$http.post('/admin/event/complete', this.completeinfo).then(res => {
    //       this.events_list[this.completeinfo.eventkey].expense = res.data;
    //       this.events_list[this.completeinfo.eventkey].status = 2;
    //       this.setEvents();
    //       this.showCompleteModal = false;
    //     })
    //   }
    // },
    // shopImg(img) {
    //   if(img.substring(0, 4) === 'http'){
    //     return true
    //   } else {
    //     return false
    //   }
    // },
    quickformswitch(showorhide) {
      this.showQuickForm = showorhide;
    }
  },
  watch: {
    showCompleted() {
      if(this.showCompleted) {
        this.setEvents(2);
      } else {
        this.setEvents(1);
      }
    }
  },
  filters: {
    formatTime(time, date) {
      return moment(date+' '+time).format('HH:mm');
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
  }
}
</script>
<style scoped>
.switch input {
    display: none;
}
.switch label {
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.switch label:before {
    padding: 6px 10px;
    content: 'O N';
    border-radius: 6px 0 0 6px;
    background: linear-gradient(to bottom, #F0F0F0 0%, #DDD 100%);
    box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.1) inset;
    color: #333;
}
.switch label:after {
    padding: 6px 10px;
    content: 'OFF';
    border-radius: 0 6px 6px 0;
    background: #C30;
    box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.1) inset;
    color: #FFF;
}
.switch input + label:hover:before {
    opacity: 0.5;
}
.switch input:checked + label:before {
    background: #9C0;
    color: #FFF;
    opacity: 1;
}
.switch input:checked + label:after {
    background: linear-gradient(to bottom, #F0F0F0 0%, #DDD 100%);
    color: #333;
}
.switch input:checked + label:hover:after {
    opacity: 0.5;
}
</style>
