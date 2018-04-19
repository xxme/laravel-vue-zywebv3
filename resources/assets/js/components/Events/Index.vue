<template>
  <div class="row">
    <universe-nav :otherMonth="otherMonth" :showYm="showYm" :events="events_list" v-on:update-events="get_events"></universe-nav>
    <div class="col-md-12">
      <div v-if="events_list.length > 0">
        <div v-for="(event, key) in events_list" :key="event.id">
          <div v-if="!events_list[key - 1] || event.event_date != events_list[key - 1].event_date" class="box-header with-border">
              <a :name="formatDateByHyphen(event.event_date)"></a>
              <h4>{{ formatDateWithWeekname(event.event_date) }} <router-link :to="'/admin/event/create/'+formatDateByHyphen(event.event_date)"><i class="fa fa-plus-square pull-right"></i></router-link></h4>
          </div>
          
          <!-- box -->
          <div class="box box-widget">
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
                <span>{{ formatDateWithWeekname(event.event_date) }}</span>
                
                <span v-if="event.apm == 1">{{ $t('event.morning') }}</span>
                <span v-else-if="event.apm == 2">{{ $t('event.afternoon') }}</span>
                <span v-else-if="event.apm == 3">{{ $t('event.night') }}</span>
                <span v-else-if="event.apm == 4">{{ $t('event.allday') }}</span>
                <span v-else>{{ event.start_time | formatTime(event.event_date) }}〜{{ event.end_time | formatTime(event.event_date) }}</span>
              </h4>
              <h5 v-if="event.typenames.length > 0">
                <span v-for="typename in event.typenames" class="label label-success"><i class="fa fa-tag"></i> {{ typename }}</span>
              </h5>
              <h5 v-if="event.carefulnames.length > 0">
                <span v-for="carefulname in event.carefulnames" class="label label-danger"><i class="fa fa-exclamation-triangle"></i> {{ carefulname }}</span>
              </h5>
              <h5 v-if="event.goods.length > 0 || event.totalname.length > 0">
                <span v-if="event.totalname" class="label label-primary"><i class="fa fa-cubes"></i> {{ event.totalname }}</span>
                <span v-for="goodsname in event.goods" class="label label-primary"><i class="fa fa-cube"></i> {{ goodsname }}</span>
              </h5>
              <h4 v-if="event.amount">
                <span class="marginr3">{{ event.amount | formatNumberJPY }}</span>
                <small>
                  <span v-if="event.agent_id" class="marginr3"><i class="fa fa-male"></i> #{{ event.agent_id }}</span>
                  <span v-if="event.shopping_id" class="marginr3"><i class="fa fa-list-alt"></i> #{{ event.shopping_id }}</span>
                  <span v-if="event.details.phone" class="marginr3"><i class="fa fa-phone"></i>  <a :href="'tel:' + event.details.phone">{{ event.details.phone }}</a></span>
                  <span v-if="event.details.wechat"><i class="fa fa-wechat"></i>  {{ event.details.wechat }}</span>
                </small>
              </h4>

              <div class="row invoice-info">
                <div v-if="event.details.from_address" class="col-sm-6 invoice-col">
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
                <div v-if="event.details.to_address" class="col-sm-6 invoice-col">
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
              <div v-if="event.images.length > 0" class="gallery">
                <div v-for="imgfile in event.images" class="col-xs-2 padding0 marginb8">
                  <a :href="'/uploads/' + imgfile | toBiggerImg" class="thumbnail" :title="imgfile | truncate(25)">
                    <img :src="'/uploads/' + imgfile" :alt="imgfile">
                  </a>
                </div>
              </div>

              <span class="text-muted">{{ event.comments.length }} comments</span>
              <div class="pull-right">
                <a href="#" class="btn-box-tool blue">
                  <i class="fa fa-pencil"></i>
                </a>
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
                  {{ comment.user.name }}
                  <span class="text-muted pull-right">{{ comment.created_at }}</span>
                </span><!-- /.username -->
                  {{ comment.content }}
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.box-comment -->
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
              <form action="#" method="post">
                <img v-if="auth.profileimg" class="img-responsive img-circle img-sm" :src="'/uploads/profiles/' + auth.profileimg">
                <img v-else class="img-responsive img-circle img-sm" src="/images/no-image-available.jpeg">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push input-group">
                  <input type="text" class="form-control" placeholder="Press enter to post comment">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat">Send!</button>
                  </span>
                </div>
              </form>
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
        otherMonth: false,
        showYm: moment().format('YYYY-MM')
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
          this.events_list =  res.data.events;
          this.auth =  res.data.auth;
      })
    },
    makered(eventid) {
      var dom = $(".col-md-12").find("[data-box='box" + eventid + "']");
      if(dom.hasClass('makered')) {
        dom.removeClass('makered');
      } else {
        $(".box-widget").each(function(i) {
          if($(this).hasClass('makered')){
            $(this).removeClass('makered');
          }
        });
        dom.addClass('makered');
      }
    },
    formatDateWithWeekname(date) {
      if(this.$i18n.locale == 'cn') {
        moment.updateLocale('cn', {weekdays: ["日","一","二","三","四","五","六"]});
        var weekname = moment(date).format('星期dddd');
        moment.updateLocale('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        weekname += moment(date).format('(dddd)');
      } else {
        moment.updateLocale('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        var weekname = moment(date).format('dddd曜日');
      }
      return moment(date).format('YYYY年MM月DD日') + ' ' + weekname;
    },
    formatDateByHyphen(date) {
      return moment(date).format('YYYY-MM-DD');
    }
  },
  filters: {
    formatNumberJPY(number) {
      return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
    },
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
}
</style>
