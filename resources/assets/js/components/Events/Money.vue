<template>
  <div class="row col-md-12">
    <section class="content-header">
			<h1>
				{{ $t('finance.moneymanage') }}
				<small>{{ $t('finance.moneymanage') }}</small>
			</h1>
		</section>
		<!-- Main content -->
		<section class="content" v-show="!showEventItem">
      <sample-nav></sample-nav>
			<div class="box">
        <div class="col-xs-12" v-if="type == 1">
          <h4>未入金统计</h4>
          <table class="table">
            <tbody>
              <tr v-for="(price, name) in user">
              <td>{{ name }}</td>
              <td>{{ price | formatNumberJPY }}</td>
              </tr>
            </tbody>
          </table>
        </div>
				<div class="box-header">
					<label :class="{ checked: type == 2 }">
						<input type="radio" value="2" v-model="type">
						{{ $t('event.paymented') }}
					</label>
					<label :class="{ checked: type == 1 }">
						<input type="radio" value="1" v-model="type">
						{{ $t('event.notpayment') }}
					</label>
          <span v-if="checkedAmount > 0">{{ $t('finance.checked') }}{{ checkedAmount | formatNumberJPY }}</span>
          <button class="btn btn-xs btn-primary pull-right" @click="changestatus()">{{ $t('nav.money') }}</button>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<tbody>
							<tr>
                <th style="width: 30px"><span v-if="type == 1">#</span><span v-else><i class="fa fa-flag"></i></span></th>
								<th style="width: 100px">{{ $t('event.payee') }}</th>
                <th style="width: 60px">{{ $t('event.event') }}</th>
								<th>
                  <template v-if="type == 1">{{ $t('finance.completeDate') }}</template>
                  <template v-else>{{ $t('event.event') }}{{ $t('global.date') }}</template>
                </th>
                <th>{{ $t('finance.amount') }}</th>
                <th>{{ $t('finance.cause') }}</th>
							</tr>
              <template v-if="finances.data.length > 0">
                <tr v-for="(list, index) in finances.data">
                  <td v-if="type == 1"><input type="checkbox" :value="list.id" v-model="checkedIds" v-if="list.status == 1"></td>
                  <td v-else><i class="fa fa-check-square-o"></i></td>
                  <td>
                    <template v-if="type == 1">
                      {{ list.user.name }}
                    </template>
                    <template v-else>
                      <template v-if="list.expense">{{ list.expense.user.name }}</template>
                      <template v-else>{{ $t('event.depositjpyL') }}</template>
                    </template>
                  </td>
                  <td>
                    <span v-if="type == 1" class="blue" @click="showEvent(list.event_id)">#{{ list.event_id }}</span>
                    <span v-else class="blue" @click="showEvent(list.id)">#{{ list.id }}</span>
                  </td>
                  <td>
                    <template v-if="type == 1">{{ list.created_at }}</template>
                    <template v-else>{{ list.event_date }}</template>
                  </td>
                  <td>
                    <template v-if="type == 1">{{ list.finalprice | formatNumberJPY }}</template>
                    <template v-else>
                      <template v-if="list.expense">{{ list.expense.finalprice | formatNumberJPY }}</template>
                      <template v-else>{{ list.deposit.jpy | formatNumberJPY }}</template>
                    </template>
                  </td>
                  <td>
                    <template v-if="type == 1">{{ list.cause }}</template>
                    <template v-else-if="list.expense">{{ list.expense.cause }}</template>
                  </td>
                </tr>
              </template>
              <template v-else>
								<tr>
									<td colspan="6"><span id="listrs">{{ $t('global.loading') }}</span></td>
								</tr>
							</template>
            </tbody>
          </table>
          <div class="marginlr10 pull-right">
						<pagination :data="finances" :limit="2" @pagination-change-page="getList"></pagination>
					</div>
        </div>
      </div>
    </section>
    <loading :loadingShow="loadingShow"></loading>
    <event-item :eventdata="show_list" :auth="auth" :userlist="userlist" :showflag="showEventItem" :comefromfinances="true" @showFinances="showFinances" @updatecomplete="updatecomplete"></event-item>
  </div>
</template>

<script>
import SampleNav from '../Public/SampleNav'
import Loading from '../Public/Loading'
import pagination from 'laravel-vue-pagination'
import EventItem from './EventItem.vue';

export default {
  mounted() {
		this.getList();
	},
  data () {
    return {
      type: 1,
      checkedIds: [],
      checkedAmount: 0,
      loadingShow: false,
      showEventItem: false,
      user: [],
      show_list: [],
      userlist: [],
      auth: null,
      finances: {
        data: {
          length: 0
        }
      }
    }
  },
  methods: {
    getList(page = 1) {
      this.finances.data.length = 0;
      this.$http({
        url: '/admin/events/finances/' + this.type + '?page=' + page,
        method: 'GET'
      }).then(res =>  {
        if(res.data == '403') {
          alert(this.$t('global.manageronly'));
          this.$router.push('/admin');
        }
        this.finances = res.data.data;
        if(res.data.data.data.length == 0) {
          $('#listrs').html(this.$t('global.noRes'));
        } else {
          this.user = res.data.user;
        }
      })
    },
    changestatus() {
      if(this.checkedIds.length > 0) {
        var self = this;
        var message = this.$t('finance.areyousurereceived') + this.$parent.$options.methods.formatNumberJPY(this.checkedAmount);
        if(window.confirm(message)) {
          this.loadingShow = true;
          this.$http.post('/admin/event/received', this.checkedIds).then(response => {
            this.checkedIds = [];
            this.getList();
            this.loadingShow = false;
          }).catch(error => {
            alert(this.$t('global.calltheadministrator'));
            this.loadingShow = false;
          });
        }
      } else {
        alert(this.$t('global.notselectd'));
      }
    },
    showEvent(eventid) {
      this.show_list = [];
      this.$http({
        url: '/admin/event/item/' + eventid,
        method: 'GET'
      }).then(res =>  {
        console.log(res.data.events);
        this.show_list.push(res.data.events);
        this.auth = res.data.auth;
        for (var index in res.data.users) {
            var user = {};
            user.id = res.data.users[index].id;
            user.text = res.data.users[index].name;
            this.userlist.push(user);
          }
        this.showEventItem = true;
      })
    },
    updatecomplete(expense) {
      this.show_list[0].expense = expense;
      for(var index in this.finances.data) {
        if(this.finances.data[index].event_id == expense.event_id) {
          this.finances.data[index] = expense;
        }
      }
    },
    showFinances() {
      this.showEventItem = false;
    }
  },
  watch: {
		type: function() {
			this.getList();
		},
    checkedIds: function() {
      if(this.checkedIds.length > 0) {
        var amount = 0;
        for(var key in this.finances.data) {
          if(this.checkedIds.indexOf(this.finances.data[key].id) !== -1) {
            amount += parseInt(this.finances.data[key].finalprice)
          }
        }
        this.checkedAmount = amount;
      } else {
        this.checkedAmount = 0;
      }
    }
	},
  filters: {
		formatNumberJPY(number) {
			if(number) {
				return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
			}
		}
	},
  components: {
		SampleNav,
		pagination,
    EventItem,
    Loading
	}
}
</script>
