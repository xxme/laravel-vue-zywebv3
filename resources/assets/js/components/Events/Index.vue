<template>
  <div class="row">
    <universe-nav :otherMonth="otherMonth" :showYm="showYm" :events="events" :holidays="holidays" v-on:update-events="get_events" @makered="makered"></universe-nav>
    <div class="col-md-12">
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
              <div class="user-block">
                <img v-if="event.user.profileimg" class="img-circle" :src="'/uploads/profiles/' + event.user.profileimg" :alt="event.user.name">
                <img v-else class="img-circle" src="/images/no-image-available.jpeg" :alt="event.user.name">
                <span class="username">{{ event.user.name }}</span>
                <span class="description">#{{event.id}} {{ event.created_at }}<span v-if="event.created_at != event.updated_at"> / {{ event.updated_at }}</span></span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" @click="makered(event.id)" data-original-title="Mark as read">
                  <i class="fa fa-circle-o"></i></button>
                <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
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
                <small>
                  <span v-if="event.partner_id" class="marginr3"><i class="fa fa-male"></i> #{{ event.partner_id }}</span>
                  <span v-if="event.shopping_id" class="marginr3"><i class="fa fa-list-alt"></i> #{{ event.shopping_id }}</span>
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
                <div v-for="imgfile in event.images" class="col-xs-2 padding0 marginb8">
                  <a :href="'/uploads/' + imgfile | toBiggerImg" class="thumbnail" :title="imgfile | truncate(25)">
                    <img :src="'/uploads/' + imgfile" :alt="imgfile">
                  </a>
                </div>
              </div>

              <span class="text-muted">{{ event.comments.length }} comments</span>
              <div class="pull-right">
                <router-link :to="'/admin/event/' + event.id + '/edit'" class="btn-box-tool blue">
                  <i class="fa fa-pencil"></i>
                </router-link>
                <a href="#" class="btn-box-tool red">
                  <i class="fa fa-trash-o"></i>
                </a>
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
                  <button type="button" class="btn btn-info btn-flat" @click="sendcomment(event.id)">Send!</button>
                </span>
              </div>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <div v-else>
        No event in this month.<br />
        <router-link to="/admin/event/create/"><i class="fa fa-plus-square"></i> Create event</router-link>
      </div>
    </div>
    <!-- /.col-md-12 -->
  </div>

</template>
<script>

import Vue from 'vue'
import moment from 'moment'
import baguetteBox from 'baguettebox.js'
import UniverseNav from '../Public/UniverseNav'

export default {
  data() {
    return {
        auth: null,
        events_list: [],
        events: [],
        holidays: [],
        otherMonth: false,
        showYm: moment().format('YYYY-MM-DD')
    }
  },
  updated: function () {
    this.$nextTick(function () {
      baguetteBox.run('.gallery');
    })
  },
  components: {
    UniverseNav
  },
  methods: {
    get_events(ym) {
      this.$http({
          url: '/admin/events/' + ym,
          method: 'GET'
      }).then(res =>  {
          this.holidays = res.data.holidays;
          this.events_list =  res.data.events;
          this.auth =  res.data.auth;
          this.setEvents();
      })
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
      if(this.holidays[date]) {
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
h5 {
  margin:3px 0;
}
</style>
