<template>
    <div>
        <button type="button" class="btn btn-primary btn-sm" @click="uploadimg">
            <i class="fa fa-smile-o"></i> Upload images
        </button>
        <input type="file" id="file" @change="onFileChange" :accept="fileAccept" style="display: none" />
    </div>
</template>

<script>
    export default {
        props: ['files', 'fileAccept'], 
        data() {
            return {
                postFormData: new FormData(),
            };
        },
        methods: {
            onFileChange(event) {
                this.postFormData.append('file', event.target.files[0]);
                this.$http.post('/admin/uploadimg', this.postFormData)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            uploadimg() {
                $('#file').click();
            }
        }
    }
</script>
