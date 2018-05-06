<template>
    <div class="margintb10">
        <button type="button" class="btn btn-outline-primary" @click="$router.push('/admin')"><i class="fa fa-calendar"></i> {{ $t('event.event') }}</button>
        <button type="button" class="btn btn-outline-primary"><i class="fa fa-money"></i> {{ $t('nav.estimateslist') }}</button>
        <button type="button" class="btn btn-outline-primary"><i class="fa fa-empire"></i> {{ $t('nav.tasklist') }}</button>
        <button type="button" class="btn btn-xs pull-right bg-blue" @click="gotocreate"><i class="fa fa-plus"></i></button>
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