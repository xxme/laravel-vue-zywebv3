<template>
    <div>
        <button type="button" class="btn bg-olive btn-sm" @click="uploadimg">
            <i class="fa fa-image"></i> Upload images
        </button>
        <input type="file" id="file" @change="onFileChange" :accept="fileAccept" style="display: none" />
    </div>
</template>

<script>
    export default {
        props: ['fileAccept'], 
        data() {
            return {
                postFormData: new FormData(),
            };
        },
        methods: {
            onFileChange(event) {
                var self = this; //*** very important if can't call parent update fun
                self.$emit('update-loading', true);
                this.postFormData.append('file', event.target.files[0]);
                this.$http.post('/admin/uploadimg', this.postFormData)
                .then(function (response) {
                    if(!response.data.errors) {
                        self.$emit('update-file', response.data);
                    } else {
                        self.$emit('error-push', response.data.errors.file[0]);
                    }
                    self.$emit('update-loading', false);
                })
                .catch(function (error) {
                    self.$emit('error-push', error);
                    self.$emit('update-loading', false);
                });
            },
            uploadimg() {
                $('#file').click();
            }
        }
    }
</script>
