<template>
    <div class="margintb10">
        <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin')"><i class="fa fa-calendar"></i> {{ $t('event.event') }}</button>
        <button type="button" class="btn btn-outline-primary" v-if="$route.path != '/admin/estimate'" @click="$router.push('/admin/estimate')"><i class="fa fa-comments-o"></i> {{ $t('nav.estimateslist') }}</button>
        <button type="button" class="btn btn-outline-primary" v-if="$route.path != '/admin/productlist'" @click="$router.push('/admin/productlist')"><i class="fa fa-shopping-cart"></i> {{ $t('nav.shoppinglist') }}</button>
        <button type="button" class="btn btn-xs pull-right bg-blue" v-if="linkurl" @click="gotocreate"><i class="fa fa-plus"></i></button>
    </div>
</template>
<script>
export default {
    props: ['linkurl'],
    methods: {
        gotocreate() {
            this.$router.push(this.linkurl)
        }
    }
}
</script>