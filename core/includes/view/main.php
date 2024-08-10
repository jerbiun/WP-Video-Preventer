<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/quasar@2.16.8/dist/quasar.prod.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/@vuepic/vue-datepicker@latest/dist/main.css">
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable"></script>


<div class="wrap">
    <h1>Video Preventer </h1>
    <div id="q-app">
        <div class="q-pt-md q-pr-md">

            <q-layout view="lHh lpr lFf" container style="height: 5000px" class="   bg-white">


                <q-page-container>
                    <q-page class="q-pa-sm">


                        <q-table
                            style="width:100%"
                            ref="myTable"
                            :rows="data"
                            :columns="cols"
                            row-key="id"
                            dense
                            :pagination="{
                            sortBy: 'name',
                            descending: false,
                            page: 1,
                            rowsPerPage: 50
                            }"
                            :filter="filter">

                            <template v-slot:body-cell-video="props">
                                                <q-td :props="props">
                                                    <div >
                                                  
                                                        <q-video
      :src="props.row.url"
    ></q-video>

                                                    </div>
                                                </q-td>
                                            </template>

                            <template v-slot:top-right>

                                <q-btn borderless   color="primary" label="Add Video +" @click="dialog = true">

                                </q-btn>

                            </template>

                        </q-table>
                        <q-dialog v-model="dialog">
             
                    <q-card style="min-width: 350px">
                    <q-form
                    @submit.prevent="onSubmit"
                    ref="myForm"
                    class="q-gutter-md">
                        <q-card-section class="row items-center q-pb-none">
                            <div class="text-h6">New Video</div> <q-space></q-space>
                            <q-btn icon="close" flat round dense v-close-popup></q-btn>

                        </q-card-section>

                        <q-card-section class="q-pt-none">
                            <q-input dense labe="Video Url" v-model="video.url" borderless :rules="[val => !!val || 'Field is required']"></q-input>
                        </q-card-section>

                        <q-card-actions align="right">
                            <q-btn borderless icon="check" label="Save" color="primary" type="submit"></q-btn>
                        </q-card-actions>
                        </q-form>
                    </q-card>
          
            </q-dialog>

                    </q-page>
                </q-page-container>
            </q-layout>
         
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.16.8/dist/quasar.umd.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.16.8/dist/lang/fr.umd.prod.js"></script>
    <script src="https://unpkg.com/@vuepic/vue-datepicker@latest"></script>
    <script type="module">
        const {
            jsPDF
        } = window.jspdf;

        const {
            ref,
            onMounted,
            computed
        } = Vue
        const {
            useQuasar,
            date
        } = Quasar
        const ajaxConfig = {
            url: ajaxurl,
            dataType: 'json',
            method: 'POST',
        }

        const app = Vue.createApp({
            components: {
                Datepicker: VueDatePicker
            },
            setup() {

                const cols = ref([{
                        name: 'video',
                        label: 'Video',
                        field: 'video',
                        align: 'left',
                        sortable: true
                    },
                    {
                        name: 'url',
                        label: 'Url',
                        field: 'url',
                        align: 'left',
                        sortable: true
                    },
                    {
                        name: 'actions',
                        label: 'Actions',
                        field: 'actions',
                        align: 'left',
                        sortable: true
                    },

                ])
                const $q = useQuasar()
                const filterData = ref({})
                const data = ref([])
                const dialog = ref(false)
                const video = ref({})
                const myForm = ref(null)

                onMounted(async () => {
                    await getVideos()

                })


                async function getVideos() {
                    $q.loading.show()
                    ajaxConfig.data = {
                        action: "wpvideopre_get_videos"
                    }

                    ajaxConfig.success = function(resp) {
                        console.log(resp)

                        data.value = resp.data
                    }

                    await jQuery.ajax(ajaxConfig)
                    $q.loading.hide()
                }
                async function addVideos() {
                    $q.loading.show()
                    ajaxConfig.data = {
                        action: "wpvideopre_add_videos",
                        video: video.value
                    }

                    ajaxConfig.success = function(resp) {
                        console.log(resp)

                   
                    }

                    await jQuery.ajax(ajaxConfig)
                    $q.loading.hide()
                }

                const onSubmit = () => {
                    myForm.value.validate().then(success => {
                        if (success) {
                            addVideos()
                        }

                    })
                }
                return {
                    onSubmit,
                    addVideos,
                    video,
                    myForm,
                    cols,
                    filter: ref(''),

                    data,

                    filterData,
                    date,
                    dialog
                }
            }
        })

        app.use(Quasar)
        Quasar.lang.set(Quasar.lang.fr)
        app.mount('#q-app')
    </script>
</div>