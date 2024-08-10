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
                    <q-page  class="q-pa-sm">


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
                            :filter="filter"
                            >

                      

                            <template v-slot:top-right>
                               
                                <q-btn borderless dense color="primary" label="Add Video +" @click="dialog = true">
                               
                                </q-btn>

                            </template>

                        </q-table>


                    </q-page>
                </q-page-container>
            </q-layout>
<q-dialog v-model="dialog">
<q-card style="min-width: 350px">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">New Video</div> <q-space  ></q-space>
          <q-btn icon="close" flat round dense v-close-popup  ></q-btn>

        </q-card-section>

        <q-card-section class="q-pt-none">
            <q-input dense labe="Video Url" v-model="video.url" borderless></q-input>
         </q-card-section>

        <q-card-actions align="right">
          <q-btn icon="check"   label="Save" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
</q-dialog>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.16.8/dist/quasar.umd.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.16.8/dist/lang/fr.umd.prod.js"></script>
    <script src="https://unpkg.com/@vuepic/vue-datepicker@latest"></script>
    <script type="module">

const {jsPDF} = window.jspdf;

const {ref, onMounted, computed} = Vue
const {useQuasar, date} = Quasar
const ajaxConfig = {
    url: ajaxurl,
    dataType: 'json',
    method: 'POST',
}

const app = Vue.createApp({
    components: {Datepicker: VueDatePicker},
    setup() {

        const cols = ref([
            {name: 'video', label: 'Video', field: 'video', align: 'left', sortable: true},
            {name: 'url', label: 'Url', field: 'url', align: 'left', sortable: true},
            {name: 'actions', label: 'Actions', field: 'actions', align: 'left', sortable: true},
         
        ])
        const $q = useQuasar()
        const filterData = ref({})
        const data = ref([])
       const dialog = ref(false)
            const video  =ref({})


        onMounted(async () => {
            await getVideos()
           
        })


        async function getVideos() {
            $q.loading.show()
            ajaxConfig.data = {action: "wpvideopre_get_videos"}

            ajaxConfig.success = function (resp) {
                console.log(resp)

                data.value = resp.data
            }

            await jQuery.ajax(ajaxConfig)
            $q.loading.hide()
        }
 
        return {
            
          
            video,
    
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