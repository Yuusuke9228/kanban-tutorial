<template>
    <transition name="modal">
        <div class="overlay" @click="$emit('close')">
            <div class="panel" @click.stop>
                <form
                    class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden"
                    @submit.prevent="handleAddNewStatus"
                >
                    <div class="text-center text-gray-500 mb-6">
                        <h3>Status Add</h3>
                    </div>
                    <div class="p-3 flex-1">
                        <input
                            class="block w-full px-2 py-1 text-lg border-b border-blue-800 rounded"
                            type="text"
                            placeholder="タイトルを入力"
                            v-model.trim="newStatus.title"
                        />
                        <input
                            class="mt-3 p-2 block w-full px-2 py-1 text-lg border-b border-blue-800 rounded"
                            type="text"
                            placeholder="スラッグを入力"
                            v-model.trim="newStatus.slug"
                        />
                        <div v-show="errorMessage">
                            <span class="text-xs text-red-500">
                              {{ errorMessage }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3 flex justify-between items-end text-sm bg-gray-100">
                        <button
                            @click="$emit('status-canceled')"
                            type="reset"
                            class="py-1 leading-5 text-gray-600 hover:text-gray-700"
                        >
                            cancel
                        </button>
                        <button
                            type="submit"
                            class="px-3 py-1 leading-5 text-white bg-orange-600 hover:bg-orange-500 rounded"
                        >
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        maxOrderNo: Number
    },
    data () {
        return {
            newStatus: {
                title: "",
                slug: "",
                order: null
            },
            errorMessage: "",
        };
    },
    mounted () {
        this.newStatus.order = this.maxOrderNo + 1;
    },
    methods: {
        handleAddNewStatus () {
            if (!this.newStatus.title) {
                this.errorMessage = "タイトルは必須です";
                return;
            }
            axios
                .post("/statuses", this.newStatus)
                .then(res => {
                    this.$emit("status-added", res.data);
                })
                .catch(err => {
                    this.handleErrors(err);
                });
        },
        handleErrors (err) {
            if (err.response && err.response.status === 422) {
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorMessage = errorBag.description[0];
                } else {
                    this.errorMessage = err.response.message;
                }
            } else {
                console.log(err.response);
            }
        }
    }
}
</script>

<style>
.overlay {
    background: rgba(0, 0, 0, .8);
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 900;
    transition: all .5s ease;
}

.panel {
    width: 300px;
    height: 250px;
    background: #fff;
    padding: 20px;
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -150px;
    margin-top: -100px;
    transition: all .3s ease;
}
.modal-enter, .modal-leave-active {
    opacity: 0;
}

.modal-enter .panel, .modal-leave-active .panel{
    top: -200px;
}
</style>
