<template>
  <div class="row">
    <universe-nav :otherMonth="otherMonth" :showYm="showYm" :events="events_list" v-on:update-events="get_events"></universe-nav>
    <div class="col-md-12">
      <div v-for="(event, key) in events_list" :key="event.id">
        <div v-if="!events_list[key - 1] || event.event_date != events_list[key - 1].event_date" class="box-header with-border">
            <a :name="event.event_date"></a>
            <h4>{{ event.event_date }} 星期四(木) <router-link :to="'/admin/event/create/'+event.event_date"><i class="fa fa-plus-square pull-right"></i></router-link></h4>
        </div>
        
        <!-- box -->
        <div class="box box-widget">
          <!-- box-header -->
          <div class="box-header with-border">
            <div class="user-block">
              <img v-if="event.user.profileimg" class="img-circle" :src="'/uploads/profiles/' + event.user.profileimg" :alt="event.user.name">
              <img v-else class="img-circle" src="/images/no-image-available.jpeg" :alt="event.user.name">
              <span class="username">{{ event.user.name }}</span>
              <span class="description">{{ event.created_at }}</span>
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
            <h4>{{ event.event_date | moment }}</h4>
            <h5>Aコース / 送货 {{ event.details.from.address }}</h5>
            <img class="img-responsive pad" src="">

            <p>I took this photo this morning. What do you guys think?</p>
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
    }
  },
  filters: {
    moment: function (date) {
      // if(stime && etime) {

      // } else if(stime) {

      // }
      return moment(date).format('YYYY/MM/DD') + moment(date).day();
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
