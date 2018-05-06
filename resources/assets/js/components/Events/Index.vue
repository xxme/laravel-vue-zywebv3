<template>
  <div>
    <section class="content-header">
			<h1>
				{{ $t('event.pagetitle') }}
				<small>{{ $t('event.subtitle') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content">
      <universe-nav :otherMonth="otherMonth" :showYmd="showYmd" :events="events" :holidays="holidays" :fee="fee" v-on:update-events="get_events" @makered="makered"></universe-nav>
      <div class="col-md-12 no-padding">
        <div v-if="events_list.length > 0">
          <div v-for="(event, key) in events_list" :key="event.id">
            <div v-if="!events_list[key - 1] || event.event_date != events_list[key - 1].event_date" class="box-header with-border">
                <a :name="event.event_date"></a>
                <h4>{{ formatDateWithWeekname(event.event_date) }} <router-link :to="'/admin/event/create/'+event.event_date"><i class="fa fa-plus-square pull-right"></i></router-link></h4>
            </div>
            
            <!-- box -->
            <div class="box box-widget" :id="'event' + event.id">
              <!-- box-header -->
              <div class="box-header with-border">
                <div v-if="event.status == 1 || !event.expense" class="user-block">
                  <img v-if="event.user.profileimg" class="img-circle" :src="'/uploads/profiles/' + event.user.profileimg" :alt="event.user.name">
                  <img v-else class="img-circle" src="/images/no-image-available.jpeg" :alt="event.user.name">
                  <span class="username">{{ event.user.name }}</span>
                  <span class="description">
                    #{{event.id}} {{ event.created_at }}
                    <span v-if="event.created_at != event.updated_at"> / {{ event.updated_at }} </span>
                    <span v-if="!event.expense" class="paddingl3"><i class="fa fa-trash-o red" @click="deleteevent(event.id, event.product_list_id)"></i></span>
                  </span>
                </div>
                <div v-else class="user-block">
                  <span v-if="event.expense.user.name">{{ event.expense.user.name }}</span>
                  {{ $t('event.receipt') }}:
                  <span v-if="event.expense.finalprice" class="marginr3">{{ formatNumberJPY(event.expense.finalprice) }}</span>
                  <span v-else class="marginr3">0</span>
                  {{ $t('event.expenditure') }}:
                  <span v-if="event.expense.expenditure" class="marginr3">{{ formatNumberJPY(event.expense.expenditure) }}</span>
                  <span v-else class="marginr3">0</span>
                  <div v-if="event.partner_id">
                    <h4 v-if="!event.expense.fxrmb && !event.expense.fxjpy" class="red">{{ $t('event.unpaid') }} <small><i class="fa fa-male"></i> #{{event.partner_id}}</small></h4>
                    <span v-else>
                      {{ $t('event.fx') }}
                      <span v-if="event.expense.fxrmb">{{ $t('event.rmb') }}:{{ formatNumberJPY(event.expense.fxrmb) }}</span>
                      <span v-if="event.expense.fxjpy">{{ $t('event.jpy') }}:{{ formatNumberJPY(event.expense.fxjpy) }}</span>
                    </span>
                  </div>
                </div>
                <!-- /.user-block -->
                <div class="box-tools">
                  <button type="button" class="btn btn-box-tool" @click="makered(event.id)">
                    <i class="fa fa-circle-o"></i></button>
                  <button type="button" class="btn btn-box-tool" @click="event.status = !event.status">
                    <i v-if="event.status == 1" class="fa fa-minus"></i>
                    <i v-else class="fa fa-window-maximize"></i>
                  </button>
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              
              <div v-show="event.status == 1">
              <div class="box-body" :data-box="'box' + event.id">
                  <div v-if="event.expense">
                    <img class="completed" src="/images/completed.png">
                  </div>
                <h4 class="eventdate">
                  <span :class="[getDateColor(event.event_date) ? getDateColor(event.event_date) == 1 ? 'red' : 'blue' : '']">
                    {{ formatDateWithWeekname(event.event_date) }}
                  </span>
                  
                  <span v-if="event.apm == 1">{{ $t('event.morning') }}</span>
                  <span v-else-if="event.apm == 2">{{ $t('event.afternoon') }}</span>
                  <span v-else-if="event.apm == 3">{{ $t('event.night') }}</span>
                  <span v-else-if="event.apm == 4">{{ $t('event.alldayT') }}</span>
                  <span v-else>
                    <span v-if="event.start_time">{{ event.start_time | formatTime(event.event_date) }}</span>
                    〜
                    <span v-if="event.end_time">{{ event.end_time | formatTime(event.event_date) }}</span>
                  </span>
                </h4>
                <h5 v-if="event.typenames && event.typenames.length > 0">
                  <span v-if="event.trucks && event.trucks.length > 0"><span v-for="truckname in event.trucks" class="label label-success"><i class="fa fa-truck"></i> {{ truckname }}</span></span>
                  <span v-for="typename in event.typenames" class="label label-success"><i class="fa fa-tag"></i> {{ typename }}</span>
                </h5>
                <h5 v-if="event.carefulnames && event.carefulnames.length > 0">
                  <span v-for="carefulname in event.carefulnames" class="label label-danger"><i class="fa fa-exclamation-triangle"></i> {{ carefulname }}</span>
                </h5>
                <h5 v-if="(event.goods && event.goods.length > 0) || (event.totalname && event.totalname.length > 0)">
                  <span v-if="event.totalname" class="label label-primary"><i class="fa fa-cubes"></i> {{ event.totalname }}</span>
                  <span v-for="goodsname in event.goods" class="label label-primary"><i class="fa fa-cube"></i> {{ goodsname }}</span>
                </h5>
                <h4>
                  <span v-if="event.amount" class="marginr3">{{ formatNumberJPY(event.amount) }}</span>
                  <small>
                    <span v-if="event.partner_id" class="marginr3"><i class="fa fa-male"></i> #{{ event.partner_id }}</span>
                    <span v-if="event.product_list_id && event.productlist" class="marginr3"><i class="fa fa-shopping-cart"></i><router-link :to="'/admin/productlist/'+event.product_list_id+'/edit'"> #{{ event.product_list_id }}{{ formatNumberJPY(event.productlist.price) }}</router-link></span>
                    <span v-if="event.details && event.details.phone" class="marginr3"><i class="fa fa-phone"></i>  <a :href="'tel:' + event.details.phone">{{ event.details.phone }}</a></span>
                    <span v-if="event.details && event.details.wechat"><i class="fa fa-wechat"></i>  {{ event.details.wechat }}</span>
                  </small>
                </h4>

                <div class="row invoice-info">
                  <div v-if="event.details && event.details.from_address" class="col-sm-6 invoice-col">
                    From
                    <address>
                      <strong>{{ event.details.from_address }}</strong>
                      <span v-if="event.details.from_elevator == 1"> {{ $t('event.elevator') }}</span>
                      <span v-else-if="event.details.from_elevator == 2"> {{ $t('event.stairs') }}</span>
                      <span v-else-if="event.details.from_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
                      <span v-if="event.details.from_floor > 0 && event.details.from_floor < 10"> {{ event.details.from_floor }} {{ $t('event.floor') }}</span>
                      <span v-else-if="event.details.from_floor > 9"> {{ event.details.from_floor }}+ {{ $t('event.floor') }}</span>
                    </address>
                  </div>
                  <!-- /.col -->
                  <div v-if="event.details && event.details.to_address" class="col-sm-6 invoice-col">
                    To
                    <address>
                      <strong>{{ event.details.to_address }}</strong>
                      <span v-if="event.details.to_elevator == 1"> {{ $t('event.elevator') }}</span>
                      <span v-else-if="event.details.to_elevator == 2"> {{ $t('event.stairs') }}</span>
                      <span v-else-if="event.details.to_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
                      <span v-if="event.details.to_floor > 0 && event.details.to_floor < 10"> {{ event.details.to_floor }} {{ $t('event.floor') }}</span>
                      <span v-else-if="event.details.to_floor > 9"> {{ event.details.to_floor }}+ {{ $t('event.floor') }}</span>
                    </address>
                  </div>
                  <!-- /.col -->
                </div>
                <div v-if="event.images && event.images.length > 0" class="gallery">
                  <div v-for="imgfile in event.images" class="col-xs-2 no-padding marginb8">
                    <template v-if="shopImg(imgfile)">
                    <a :href="imgfile" class="thumbnail" :title="imgfile | truncate(25)">
                      <img :src="imgfile" :alt="imgfile">
                    </a>
                    </template>
                    <template v-else>
                      <a :href="'/uploads/' + imgfile | toBiggerImg" class="thumbnail" :title="imgfile | truncate(25)">
                        <img :src="'/uploads/' + imgfile" :alt="imgfile">
                      </a>
                    </template>
                  </div>
                </div>

                <span class="text-muted">{{ event.comments.length }} comments</span>
                <div class="pull-right">
                    <button class="btn btn-xs btn-warning" @click="$router.push('/admin/event/' + event.id + '/edit')">
                      <i class="fa fa-pencil"></i>
                    </button>
                    <button class="btn btn-xs btn-success" @click="completeEvent(event.id)">
                      <i class="ace-icon fa fa-check-square-o"></i>
                    </button>
                </div>
              </div>
                
              <!-- /.box-body -->
              <div v-if="event.comments.length > 0" class="box-footer box-comments">
                <div v-for="comment in event.comments" class="box-comment">
                  <!-- User image -->
                  <img v-if="comment.user.profileimg" class="img-circle img-sm" :src="'/uploads/profiles/' + comment.user.profileimg">
                  <img v-else class="img-circle img-sm" src="/images/no-image-available.jpeg">

                  <div class="comment-text">
                    <span class="username">
                      {{ comment.user.name }} <span class="text-muted">{{ comment.created_at }}</span>
                      <span class="pull-right"><i class="fa fa-trash-o btn-box-tool red" @click="deletecomment(comment.id)"></i></span>
                    </span><!-- /.username -->
                    {{ comment.content }}
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.box-comment -->
              </div>
              <!-- /.box-footer -->
              <div class="box-footer">
                <img v-if="auth.profileimg" class="img-responsive img-circle img-sm" :src="'/uploads/profiles/' + auth.profileimg">
                <img v-else class="img-responsive img-circle img-sm" src="/images/no-image-available.jpeg">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push input-group">
                  <input type="text" class="form-control" :id="'comment' + event.id" placeholder="Press enter to post comment">
                  <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" @click="sendcomment(event.id)">{{ $t('global.send') }}</button>
                  </span>
                </div>
              </div>
              <!-- /.box-footer -->
            </div>
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div v-else>
          <span id="eventrs">{{ $t('global.loading') }}</span><br />
          <router-link to="/admin/event/create/"><i class="fa fa-plus-square"></i> Create event</router-link>
        </div>
      </div>
      <!-- /.col-md-12 -->
      <transition name="modal">
        <div v-show="showCompleteModal" class="modal-mask form-horizontal">
          <div class="modal-wrapper">
            <div class="modal-container">

              <div class="modal-header">
                <slot name="header">
                  {{ $t('event.complete') }}{{ $t('event.event') }}
                </slot>
              </div>

              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.finalprice') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.price">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zctingche') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.tingche">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zccanyin') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.canyin">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zcgaosu') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.gaosu">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zcjiayou') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.jiayou">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zcmaihuo') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.maihuo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-xs-5 control-label">{{ $t('event.zcother') }}</label>
                    <div class="col-xs-7">
                      <input type="number" class="form-control" v-model.number="completeinfo.other">
                    </div>
                  </div>
                  <div v-show="completeinfo.haspartner">
                    <div class="form-group">
                      <label class="col-xs-5 control-label">{{ $t('event.fxrmb') }}</label>
                      <div class="col-xs-7">
                        <input type="number" class="form-control" v-model.number="completeinfo.rmb">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-xs-5 control-label">{{ $t('event.fxjpy') }}</label>
                      <div class="col-xs-7">
                        <input type="number" class="form-control" v-model.number="completeinfo.jpy">
                      </div>
                    </div>
                  </div>
                  <div class="payee">
                    <div class="col-xs-6 no-padding">
                      <select2 id="payee" :options="user_list" :selected="completeinfo.payee" :placeholder="$t('event.payee')" :multiple="false"></select2>
                    </div>
                    <div class="col-xs-6 no-padding">
                      <label>
                        <input type="radio" name="status" value="1" v-model="completeinfo.sta">
                        {{ $t('event.notpayment') }} 
                      </label>
                      <label>
                        <input type="radio" name="status" value="2" v-model="completeinfo.sta">
                        {{ $t('event.paymented') }} 
                      </label>
                    </div>
                  </div>
                </slot>
              </div>

              <div class="modal-footer text-left">
                <slot name="footer">
                  <button class="btn btn-xs btn-default" @click="showCompleteModal = false">
                    {{ $t('global.Cancel') }}
                  </button>
                  <button class="btn btn-xs btn-primary pull-right" @click="completedo">
                    {{ $t('global.Submit') }}
                  </button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </section>
  </div>
</template>
<script>

import Vue from 'vue'
import moment from 'moment'
import baguetteBox from 'baguettebox.js'
import UniverseNav from '../Public/UniverseNav'
import Select2 from './Select2'

export default {
  data() {
    return {
        auth: null,
        events_list: [],
        user_list: [],
        events: [],
        holidays: [],
        otherMonth: false,
        showCompleteModal: false,
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
  updated: function () {
    this.$nextTick(function () {
      baguetteBox.run('.gallery', {
        noScrollbars: true
      });
    })
  },
  components: {
    Select2,
    UniverseNav
  },
  methods: {
    get_events(ym) {
      this.events_list = [];
      $('#eventrs').html('Loading...');
      this.$http({
          url: '/admin/events/' + ym,
          method: 'GET'
      }).then(res =>  {
          this.holidays = res.data.holidays;
          this.resetFee();
          if(res.data.events.length == 0) {
            $('#eventrs').html('No event in this month.');
          } else {
            this.events_list = res.data.events;
            this.setEvents();
            this.auth = res.data.auth;
            for (var index in res.data.users) {
              var user = {};
              user.id = res.data.users[index].id;
              user.text = res.data.users[index].name;
              this.user_list.push(user)
            }
          }
      })
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
    setEvents() {
      this.events = [];
      for(var i = 0; i < this.events_list.length; i++) {
        var eventObj = new Object();
        var amount = this.formatNumberJPY(this.events_list[i].amount);
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
        if(this.events_list[i].types.indexOf(5) > -1) {
          // 見積
          eventObj.color = '#d81b60';
        } else if (this.events_list[i].types.indexOf(19) > -1) {
          // 休み
          eventObj.title += '('+this.events_list[i].user.name+')';
          eventObj.color = '#001f3f';
        }
        this.events.push(eventObj);

        // nav finance
        if(this.events_list[i].amount || this.events_list[i].expense) {
          
          var hasuserdata = false;
          for(var index in this.fee.users) {
            if(this.fee.users[index].id == this.events_list[i].user.id) {
              hasuserdata = true;
              if(this.events_list[i].expense) {
                // completed
                this.fee.users[index].total += this.events_list[i].expense.finalprice;
                this.fee.users[index].completed += this.events_list[i].expense.finalprice;
              } else {
                // undone
                this.fee.users[index].total += this.events_list[i].amount;
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
              this.fee.finance.total += this.events_list[i].expense.finalprice;
              this.fee.finance.expenditure += this.events_list[i].expense.expenditure;
              this.fee.finance.zctingche += this.events_list[i].expense.zctingche;
              this.fee.finance.zccanyin += this.events_list[i].expense.zccanyin;
              this.fee.finance.zcgaosu += this.events_list[i].expense.zcgaosu;
              this.fee.finance.zcjiayou += this.events_list[i].expense.zcjiayou;
              this.fee.finance.zcmaihuo += this.events_list[i].expense.zcmaihuo;
              this.fee.finance.zcother += this.events_list[i].expense.zcother;
              this.fee.finance.fxrmb += this.events_list[i].expense.fxrmb;
              this.fee.finance.fxjpy += this.events_list[i].expense.fxjpy;
            } else {
              // undone
              this.fee.finance.total += this.events_list[i].amount;
              this.fee.finance.undone += this.events_list[i].amount;
            }
          }
        }
      }
    },
    makered(eventid) {
      var dom = $(".col-md-12").find("[data-box='box" + eventid + "']");
      if(dom.hasClass('makered')) {
        dom.removeClass('makered');
      } else {
        $(".box-body").each(function(i) {
          if($(this).hasClass('makered')){
            $(this).removeClass('makered');
          }
        });
        dom.addClass('makered');
      }
    },
    formatDateWithWeekname(date) {
      if(this.$i18n.locale == 'cn') {
        moment.locale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
        var weekname = moment(date).format('星期dddd');
        moment.locale('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        weekname += moment(date).format('(dddd)');
      } else {
        moment.locale('ja');
        weekname = moment(date).format('dddd');
      }
      if(this.holidays && this.holidays[date]) {
        weekname += ' '+this.holidays[date];
      }

      return moment(date).format('YYYY年MM月DD日') + ' ' + weekname;
    },
    formatNumberJPY(number) {
      if(number) {
        return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
      } else {
        return '';
      }
    },
    getDateColor(date) {
      moment.locale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
      if(moment(date).format('dddd') == '日' || this.holidays[date]) {
        return 1;
      } else if(moment(date).format('dddd') == '六') {
        return 2;
      }
      return false;
    },
    sendcomment(eventid) {
      var val = $('#comment'+eventid).val();
      if(val) {
        var formData = new FormData();
        formData.append("event_id", eventid);
        formData.append("content", val);
        this.$http.post('/admin/comment', formData).then(response => {
          var comment = response.data;
          for(var key in this.events_list) {
            if(this.events_list[key].id == eventid){
              this.events_list[key].comments.push(comment);
            }
          }
          $('#comment'+eventid).val('');
        })
      }
    },
    deleteevent(eventid, listid) {
      var message = this.$t('event.delwarning');
      if(listid) {
        message += this.$t('event.listWellBeDel')
      }
      if(window.confirm(message)) {
        this.$http.delete('/admin/event/'+eventid).then(response => {
          if(response) {
            for(var key in this.events_list) {
              if(this.events_list[key].id == eventid){
                this.events_list.splice(key, 1);
              }
            }
          }
        })
      }
    },
    deletecomment(commentid) {
      if(window.confirm('Are you sure delete comment?')) {
        this.$http.delete('/admin/comment/'+commentid).then(response => {
          if(response) {
            for(var key in this.events_list) {
              for(var k in this.events_list[key].comments) {
                if(this.events_list[key].comments[k].id == commentid){
                  var index = this.events_list[key].comments.indexOf(this.events_list[key].comments[k]);
                  this.events_list[key].comments.splice(index, 1);
                }
              }
            }
          }
        })
      }
    },
    completeEvent(eventid) {
      for(var key in this.events_list) {
        if(this.events_list[key].id == eventid) {
          if(this.events_list[key].expense) {
            this.completeinfo.tingche = this.events_list[key].expense.zctingche;
            this.completeinfo.canyin = this.events_list[key].expense.zccanyin;
            this.completeinfo.gaosu = this.events_list[key].expense.zcgaosu;
            this.completeinfo.jiayou = this.events_list[key].expense.zcjiayou;
            this.completeinfo.maihuo = this.events_list[key].expense.zcmaihuo;
            this.completeinfo.other = this.events_list[key].expense.zcother;
            this.completeinfo.rmb = this.events_list[key].expense.fxrmb;
            this.completeinfo.jpy = this.events_list[key].expense.fxjpy;
            this.completeinfo.payee = this.events_list[key].expense.user_id;
            this.completeinfo.sta = this.events_list[key].expense.status;
            this.completeinfo.price = this.events_list[key].expense.finalprice;
          } else {
            this.completeinfo.tingche = 
            this.completeinfo.canyin = 
            this.completeinfo.gaosu = 
            this.completeinfo.jiayou = 
            this.completeinfo.maihuo = 
            this.completeinfo.other = 
            this.completeinfo.rmb = 
            this.completeinfo.jpy = 0;
            this.completeinfo.payee = "";
            this.completeinfo.sta = 1;
            if(this.events_list[key].amount) {
              this.completeinfo.price = this.events_list[key].amount;
            } else {
              this.completeinfo.price = 0;
            }
          }
          this.completeinfo.eventid = eventid;
          this.completeinfo.eventkey = key;
          if(this.events_list[key].partner_id) {
            this.completeinfo.haspartner = true;
          } else {
            this.completeinfo.haspartner = false;
          }
        }
      }
      this.showCompleteModal = true;
  },
    completedo() {
      this.completeinfo.payee = $('#payee').val();
      if(this.completeinfo.payee) {
        if(this.completeinfo.sta == 1) {
          var status = this.$t('event.notpayment');
        } else {
          var status = this.$t('event.paymented');
        }
        for(var key in this.user_list) {
          if(this.user_list[key].id == this.completeinfo.payee) {
            var payeename = this.user_list[key].text;
          }
        }
        if(this.completeinfo.price > 0) {
          var finalprice = this.formatNumberJPY(this.completeinfo.price);
        } else {
          var finalprice = status = payeename = '';
        }
        if(window.confirm(this.$t('global.areYouSure') + 
            payeename + ' ' + finalprice + status
          )) {
          this.$http.post('/admin/event/complete', this.completeinfo).then(res => {
            this.events_list[this.completeinfo.eventkey].expense = res.data;
            this.events_list[this.completeinfo.eventkey].status = 2;
            this.showCompleteModal = false;
          })
        }
      } else {
        alert(this.$t('event.payee') + this.$t('global.required'));
      }
    },
    shopImg(img) {
      if(img.substring(0, 4) === 'http'){
        return true
      } else {
        return false
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
.input-group {
  width: auto;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.gallery {
  height: 85px;
  margin: 10px 0;
}
.label, .marginr3 {
  margin-right: 3px;
  font-weight: 100;
  line-height: 23px;
}
.paddingl3 {
  padding-left: 3px;
}
.form-group {
  margin-bottom: 5px;
}
.payee {
  width: 100%;
  line-height: 32px;
  height: 25px;
}
.completed {
  position: absolute;
  right: 60px;
  z-index: 10;
  opacity: 0.65;
}
h5 {
  margin:3px 0;
}
</style>
