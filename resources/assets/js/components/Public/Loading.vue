<template>
    <div v-show="loadingShow" class="LockShow"><div class="loadingicon"><i class="fa fa-rocket fa-spin fa-3x"></i> <span>{{ $t('global.loadding') }}</span></div></div>
</template>

<script>
export default {
    props: ['loadingShow']
}    
</script>