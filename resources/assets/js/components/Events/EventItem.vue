<template>
  <div>
    <div class="col-md-12 no-padding" v-show="events_list.length > 0 && showflag">
      <div v-if="events_list.length > 0">
        <div v-for="(event, key) in events_list" :key="event.id">
          <div v-if="!events_list[key - 1] || event.event_date != events_list[key - 1].event_date" class="box-header with-border">
            <h4>
              <button class="btn btn-primary btn-xs" v-if="!comefromfinances" @click="$emit('editevent', 0, event.event_date)"><i class="fa fa-plus"></i></button>
              {{ formatDateWithWeekname(event.event_date) }} <span v-show="events_list.length == 1"># {{ event.id }}</span>
              <button class="btn btn-primary pull-right bigger-120" v-if="!comefromfinances" @click="$emit('showCalendar')">{{ $t('global.goback') }}</button>
              <button class="btn btn-primary pull-right bigger-140" v-else @click="$emit('showFinances')"><i class="fa fa-money"></i></button>
            </h4>
          </div>
          
          <!-- box -->
          <div class="box box-widget">
            <!-- box-header -->
            <div class="box-header with-border">
              <div class="user-block">
                <img v-if="event.user.profileimg" class="img-circle" :src="'/uploads/profiles/' + event.user.profileimg">
                <img v-else class="img-circle" src="/images/no-image-available.jpeg">
                <span class="username">{{ event.user.name }}</span>
                <span class="description">
                  #{{event.id}} {{ event.created_at }}
                  <span v-if="event.created_at != event.updated_at"> / {{ event.updated_at }} </span>
                  <span v-if="!event.expense" class="paddingl3"><i class="fa fa-trash-o red" @click="deleteevent(event.id, event.product_list_id)"></i></span>
                </span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" v-if="!event.expense" @click="contract(event.id)">
                  <i class="fa fa-list-alt"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            
            <div :class="[event.expense ? 'completedeventbg' : '']">
            <div class="box-body" :data-box="'box' + event.id">
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
                <span v-if="event.deposit">
                  <span v-if="event.deposit.jpy > 0" class="marginr3">{{ $t('event.depositjpyL') }}{{ formatNumberJPY(event.deposit.jpy) }}</span>
                  <span v-if="event.deposit.rmb > 0" class="marginr3">{{ $t('event.depositrmbL') }}{{ formatNumberJPY(event.deposit.rmb) }}</span>
                </span>
                <small>
                  <span v-if="event.partner" class="marginr3"><i class="fa fa-male"></i> {{ event.partner }}</span>
                  <span v-if="event.product_list_id && event.productlist" class="marginr3"><i class="fa fa-shopping-cart"></i><router-link :to="'/admin/productlist/'+event.product_list_id+'/edit'"> #{{ event.product_list_id }} {{ formatNumberJPY(event.productlist.price) }}</router-link></span>
                  <span v-if="event.order_id" class="marginr3"><i class="fa fa-comments-o"></i> #{{ event.order_id }}</span>
                  <span v-if="event.details && event.details.phone" class="marginr3"><i class="fa fa-phone"></i>  <a :href="'tel:' + event.details.phone">{{ event.details.phone }}</a></span>
                  <span v-if="event.details && event.details.wechat"><i class="fa fa-wechat"></i>  {{ event.details.wechat }}</span>
                </small>
              </h4>

              <div class="row invoice-info">
                <div v-if="event.details && event.details.from_address" class="col-sm-6 invoice-col">
                  From
                  <address>
                    <strong>{{ event.details.from_address }}</strong>
                    <template v-if="event.details.from_btype">
                      <span v-if="event.details.from_btype == 1">{{ $t('offer.buildingtype1') }}</span>
                      <span v-else-if="event.details.from_btype == 2">{{ $t('offer.buildingtype2') }}</span>
                      <span v-else-if="event.details.from_btype == 3">{{ $t('offer.buildingtype3') }}</span>
                      <span v-else-if="event.details.from_btype == 4">{{ $t('offer.buildingtype4') }}</span>
                      <span v-else-if="event.details.from_btype == 5">{{ $t('offer.buildingtype5') }}</span>
                      <span v-else-if="event.details.from_btype == 6">{{ $t('offer.buildingtype6') }}</span>
                    </template>
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
                    <template v-if="event.details.to_btype">
                      <span v-if="event.details.to_btype == 1">{{ $t('offer.buildingtype1') }}</span>
                      <span v-else-if="event.details.to_btype == 2">{{ $t('offer.buildingtype2') }}</span>
                      <span v-else-if="event.details.to_btype == 3">{{ $t('offer.buildingtype3') }}</span>
                      <span v-else-if="event.details.to_btype == 4">{{ $t('offer.buildingtype4') }}</span>
                      <span v-else-if="event.details.to_btype == 5">{{ $t('offer.buildingtype5') }}</span>
                      <span v-else-if="event.details.to_btype == 6">{{ $t('offer.buildingtype6') }}</span>
                    </template>
                    <span v-if="event.details.to_elevator == 1"> {{ $t('event.elevator') }}</span>
                    <span v-else-if="event.details.to_elevator == 2"> {{ $t('event.stairs') }}</span>
                    <span v-else-if="event.details.to_elevator == 3"> {{ $t('event.elevatorAndstairs') }}</span>
                    <span v-if="event.details.to_floor > 0 && event.details.to_floor < 10"> {{ event.details.to_floor }} {{ $t('event.floor') }}</span>
                    <span v-else-if="event.details.to_floor > 9"> {{ event.details.to_floor }}+ {{ $t('event.floor') }}</span>
                  </address>
                </div>
                <!-- /.col -->
              </div>
              <div v-if="event.images && event.images.length > 0" class="gallery col-xs-12 no-padding">
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
              <div v-if="event.expense">
                <span v-if="event.expense.user.name">{{ event.expense.user.name }}</span>
                <span v-if="event.expense.cause" class="marginr3">{{ event.expense.cause }}</span>
                {{ $t('event.receipt') }}:
                <span v-if="event.expense.finalprice" class="marginr3">{{ formatNumberJPY(event.expense.finalprice) }}</span>
                <span v-else class="marginr3">0</span>
                {{ $t('event.expenditure') }}:
                <span v-if="event.expense.expenditure" class="marginr3">{{ formatNumberJPY(event.expense.expenditure) }}</span>
                <span v-else class="marginr3">0</span>
                <div v-if="event.partner">
                  <h4 v-if="!event.expense.fxrmb && !event.expense.fxjpy" class="red">{{ $t('event.unpaid') }} <small><i class="fa fa-male"></i> #{{event.partner}}</small></h4>
                  <span v-else>
                    {{ $t('event.fx') }}
                    <span v-if="event.expense.fxrmb">{{ $t('event.rmb') }}:{{ formatNumberJPY(event.expense.fxrmb) }}</span>
                    <span v-if="event.expense.fxjpy">{{ $t('event.jpy') }}:{{ formatNumberJPY(event.expense.fxjpy) }}</span>
                  </span>
                </div>
              </div>

              <span class="text-muted">{{ event.comments.length }} comments</span>
              <div class="pull-right">
                  <button class="btn btn-xs btn-warning bigger-120" v-if="!comefromfinances" @click="$emit('editevent', event.id)">
                    <i class="fa fa-pencil"></i>
                  </button>
                  <button class="btn btn-xs btn-success bigger-120" v-if="auth.group_id == 1 || !event.expense" @click="completeEvent(event.id)">
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
                <div v-if="completeinfo.price != completeinfo.receivable" class="form-group">
                  <div class="col-xs-12">
                    <textarea class="form-control" rows="2" v-model="completeinfo.cause" :placeholder="$t('event.cause')"></textarea>
                  </div>
                </div>
                <div class="payee" v-show="auth && auth.group_id == 1">
                  <div class="col-xs-6 no-padding">
                    <select2 id="payee" :options="userlist" :selected="completeinfo.payee" :placeholder="$t('event.payee')" :multiple="false"></select2>
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
  </div>
</template>

<script>
import moment from 'moment'
import baguetteBox from 'baguettebox.js'
import Select2 from './Select2'

export default {
  props: ['auth', 'eventdata', 'showflag', 'userlist', 'showCompleted', 'comefromfinances'],
  data() {
    return {
      // auth: null,
      events_list: [],
      // user_list: [],
      // events: [],
      // eventid: 0,
      holidays: [],
      showCompleteModal: false,
      // showCompleted: false,
      // showYmd: moment().format('YYYY-MM-DD'),
      completeinfo: {
        eventid: "",
        eventkey: "",
        haspartner: false,
        price: 0,
        receivable: 0,
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
      // if(!this.showfrom && this.eventid) {
      //   var pos = $("#event"+this.eventid).offset().top;
      //   $(window).scrollTop(pos);
      //   this.eventid = 0;
      // } else if(!this.showfrom && this.eventdate) {
      //   var pos = $("#date"+this.eventdate).offset().top;
      //   $(window).scrollTop(pos);
      //   this.eventdate = "";
      // }
    })
  },
  components: {
    Select2
  },
  methods: {
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
            this.completeinfo.cause = this.events_list[key].expense.cause;
            this.completeinfo.receivable = this.events_list[key].receivable;
          } else {
            this.completeinfo.tingche = 
            this.completeinfo.canyin = 
            this.completeinfo.gaosu = 
            this.completeinfo.jiayou = 
            this.completeinfo.maihuo = 
            this.completeinfo.other = 
            this.completeinfo.rmb = 
            this.completeinfo.jpy = 0;
            this.completeinfo.payee = this.completeinfo.cause = "";
            this.completeinfo.sta = 1;
            if(this.events_list[key].receivable) {
              this.completeinfo.price = this.events_list[key].receivable;
            } else {
              this.completeinfo.price = 0;
            }
          }
          this.completeinfo.eventid = eventid;
          this.completeinfo.eventkey = key;
          if(this.events_list[key].partner) {
            this.completeinfo.haspartner = true;
          } else {
            this.completeinfo.haspartner = false;
          }
          console.log(this.events_list[key]);
          if(this.events_list[key].receivable > 0) {
            this.completeinfo.receivable = this.events_list[key].receivable;
          }
        }
      }
      this.showCompleteModal = true;
    },
    completedo() {
      if(this.completeinfo.cause) {
        var cause = this.completeinfo.cause;
      } else {
        var cause = "";
      }
      
      if(this.completeinfo.receivable != this.completeinfo.price && !cause.trim()) {
        alert(this.$t('event.cause'));
        return;
      } else if(this.completeinfo.receivable == this.completeinfo.price) {
        this.completeinfo.cause = "";
      }
      if($('#payee').val()) {
        this.completeinfo.payee = $('#payee').val();
      } else {
        this.completeinfo.payee = this.auth.id;
      }
      if(this.completeinfo.sta == 1) {
        var status = this.$t('event.notpayment');
      } else {
        var status = this.$t('event.paymented');
      }
      if(this.completeinfo.payee == this.auth.id) {
        var payeename = this.$t('event.myself');
      } else {
        for(var key in this.userlist) {
          if(this.userlist[key].id == this.completeinfo.payee) {
            var payeename = this.userlist[key].text;
          }
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
          if(this.comefromfinances) {
            this.$emit('updatecomplete', res.data);
          } else {
            this.$emit('completedevent', res.data);
          }
          this.showCompleteModal = false;
        })
      }
    },
    contract(id) {
      window.open('/admin/event/contract/' + id, '_blank');
    },
    shopImg(img) {
      if(img.substring(0, 4) === 'http'){
        return true
      } else {
        return false
      }
    }
  },
  watch: {
    eventdata() {
      this.events_list = this.eventdata;
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
<style>
.img-push {
  width: auto;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.gallery {
  margin: 10px 0;
}
.label, .marginr3 {
  margin-right: 3px;
  font-weight: 100;
  line-height: 23px;
}
.label {
  display: inline-table;
  margin: 1px;
  line-height: 15px;
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
.bigger-120 {
  margin-left: 10px;
}
h5 {
  margin:3px 0;
}
label {
  margin-bottom: 0;
}
</style>