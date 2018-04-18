<template>
  <div class="row">
    <universe-nav :otherMonth="otherMonth" :showYm="showYm" :events="events_list" v-on:update-events="get_events"></universe-nav>
    <div class="col-md-12">
      <div v-for="(event, key) in events_list" :key="event.id">
        <div v-if="!events_list[key - 1] || event.event_date != events_list[key - 1].event_date" class="box-header with-border">
            <a :name="event.event_date"></a>
            <h4>{{ formatDateWithWeekname(event.event_date) }} <router-link :to="'/admin/event/create/'+event.event_date"><i class="fa fa-plus-square pull-right"></i></router-link></h4>
        </div>
        
        <!-- box -->
        <div class="box box-widget">
          <!-- box-header -->
          <div class="box-header with-border">
            <div class="user-block">
              <img v-if="event.user.profileimg" class="img-circle" :src="'/uploads/profiles/' + event.user.profileimg" :alt="event.user.name">
              <img v-else class="img-circle" src="/images/no-image-available.jpeg" :alt="event.user.name">
              <span class="username">{{ event.user.name }}</span>
              <span class="description">{{ event.created_at }}<span v-if="event.created_at != event.updated_at"> / {{ event.updated_at }}</span></span>
            </div>
            <!-- /.user-block -->
            <div class="box-tools">
              <button type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i>
              </button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <h4 class="eventdate">
              <span>{{ formatDateWithWeekname(event.event_date) }}</span>
              
              <span v-if="event.apm == 1">{{ $t('event.morning') }}</span>
              <span v-else-if="event.apm == 2">{{ $t('event.afternoon') }}</span>
              <span v-else-if="event.apm == 3">{{ $t('event.night') }}</span>
              <span v-else-if="event.apm == 4">{{ $t('event.allday') }}</span>
              <span v-else>{{ event.details.from.time }}〜{{ event.details.to.time }}</span>
            </h4>
            <h5>
              <span class="label label-success"><i class="fa fa-tag"></i> Aコース</span>
              <span class="label label-success"><i class="fa fa-tag"></i> 送货</span>
            </h5>
            <h5>
              <span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> 注意1</span>
              <span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> 注意2</span>
            </h5>

            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                From
                <address>
                  <strong>{{ event.details.from.address }}</strong><br>
                  795 Folsom Ave, Suite 600<br>
                  San Francisco, CA 94107<br>
                  Phone: (804) 123-5432<br>
                  Email: info@almasaeedstudio.com
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                To
                <address>
                  <strong>{{ event.details.to.address }}</strong><br>
                  795 Folsom Ave, Suite 600<br>
                  San Francisco, CA 94107<br>
                  Phone: (555) 539-1037<br>
                  Email: john.doe@example.com
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <b>Invoice #007612</b><br>
                <br>
                <b>Order ID:</b> 4F3S8J<br>
                <b>Payment Due:</b> 2/22/2014<br>
                <b>Account:</b> 968-34567
              </div>
              <!-- /.col -->
            </div>
            <img class="img-responsive pad" src="">

            <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
            <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
            <span class="pull-right text-muted">127 likes - 3 comments</span>
          </div>
          <!-- /.box-body -->
          <div class="box-footer box-comments">
            <div class="box-comment">
              <!-- User image -->
              <img class="img-circle img-sm" src="" alt="User Image">

              <div class="comment-text">
              <span class="username">
                Maria Gonzales
                <span class="text-muted pull-right">8:03 PM Today</span>
              </span><!-- /.username -->
                It is a long established fact that a reader will be distracted
                by the readable content of a page when looking at its layout.
              </div>
              <!-- /.comment-text -->
            </div>
            <!-- /.box-comment -->
            <div class="box-comment">
              <!-- User image -->
              <img class="img-circle img-sm" src="" alt="User Image">

              <div class="comment-text">
              <span class="username">
                  Luna Stark
                  <span class="text-muted pull-right">8:03 PM Today</span>
              </span><!-- /.username -->
                  It is a long established fact that a reader will be distracted
                  by the readable content of a page when looking at its layout.
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
    <!-- /.col-md-12 -->
  </div>

</template>
<script>

import Vue from 'vue'
import moment from 'moment'
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
  created() {

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
    formatDateWithWeekname(date) {
      if(this.$i18n.locale == 'cn') {
        moment.lang('cn', {weekdays: ["日","一","二","三","四","五","六"]});
        var weekname = moment(date).format('星期dddd');
        moment.lang('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        weekname += moment(date).format('(dddd)');
      } else {
        moment.lang('ja', {weekdays: ["日","月","火","水","木","金","土"]});
        var weekname = moment(date).format('dddd曜日');
      }
      return moment(date).format('YYYY年MM月DD日') + ' ' + weekname;
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
</style>
