<template>
    <form
        class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden"
        @submit.prevent="handleAddNewTask"
    >
        <div class="p-3 flex-1">
            <input
                class="block w-full px-2 py-1 text-lg border-b border-blue-800 rounded"
                type="text"
                placeholder="タイトルを入力"
                v-model.trim="newTask.title"
            />
            <textarea
                class="mt-3 p-2 block w-full p-1 border text-sm rounded"
                rows="2"
                placeholder="説明を追加（オプション）"
                v-model.trim="newTask.description"
            ></textarea>
            <div v-show="errorMessage">
        <span class="text-xs text-red-500">
          {{ errorMessage }}
        </span>
            </div>
        </div>
        <div class="p-3 flex justify-between items-end text-sm bg-gray-100">
            <button
                @click="$emit('task-canceled')"
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
</template>

<script>
export default {
    props: {
        statusId: Number
    },
    data() {
        return {
            newTask: {
                title: "",
                description: "",
                order: 0,
                status_id: null
            },
            errorMessage: ""
        };
    },
    mounted () {
        this.newTask.status_id = this.statusId;
    },
    methods: {
        handleAddNewTask() {
            // 空のタスクをサーバーに送信しないようにするための基本的な検証
            if (!this.newTask.title) {
                this.errorMessage = "タスクタイトルは必須です";
                return;
            }
            // 新しいタスクをサーバーに送信
            axios
                .post("/tasks", this.newTask)
                .then(res => {
                    // 新しいタスクを追加したことを親コンポーネントに伝え、それを含めます
                    this.$emit("task-added", res.data);
                })
                .catch(err => {
                    // リクエストから返されたエラーを処理する
                    this.handleErrors(err);
                });
        },
        handleErrors (err) {
            if (err.response && err.response.status === 422) {
                // リクエストから返されたエラーを処理する
                const errorBag = err.response.data.errors;
                if (errorBag.title) {
                    this.errorMessage = errorBag.title[0];
                } else if (errorBag.description) {
                    this.errorMessage = errorBag.description[0];
                } else {
                    this.errorMessage = err.response.message;
                }
            } else {
                // 422以外のエラーが返された場合、レスポンスをコンソールへ出力します
                console.log(err.response);
            }
        }
    }
};
</script>
