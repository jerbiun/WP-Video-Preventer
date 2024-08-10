<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet" type="text/css">
<link href="https://cdn.jsdelivr.net/npm/quasar@2.12.7/dist/quasar.prod.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://unpkg.com/@vuepic/vue-datepicker@latest/dist/main.css">
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable"></script>
 

<div class="wrap">
    <h1>Gestion de Stock </h1>
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
                                <!--                                 <q-select
                                         
                                        v-model="cat"
                                        use-input
                                        input-debounce="0"
                                        label="Categories"
                                        :options="cats"
                                        @filter="filterFn"
                                        style="width: 250px"
                                      >
                                        <template v-slot:no-option>
                                          <q-item>
                                            <q-item-section class="text-grey">
                                              No results
                                            </q-item-section>
                                          </q-item>
                                        </template>
                                      </q-select>-->
                                <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
                                    <template v-slot:append>
                                        <q-icon name="search" ></q-icon>
                                    </template>
                                </q-input>

                            </template>
                            <template v-slot:header="props">
                                <q-tr :props="props">
                                    <q-th auto-width  ></q-th>
                                    <q-th
                                        v-for="col in props.cols"
                                        :key="col.name"
                                        :props="props"
                                        >
                                        {{ col.label }}
                                    </q-th>
                                </q-tr>
                            </template>
                            <template v-slot:body="props">
                                <q-tr :props="props">
                                    <q-td auto-width>
                                        <q-btn v-if='props.row.type=="variable"' size="sm" color="accent" round dense
                                               @click="props.expand = !props.expand" :icon="props.expand ? 'remove' : 'add'" />
                                    </q-td>
                                    <q-td
                                        v-for="col in props.cols"
                                        :key="col.name"
                                        :props="props"
                                        >
                                        <div v-if="col.name=='ticket'">
                                            <div  v-if='props.row.type=="simple" || props.row.surmesure=="1"  '>
                                                <!--      <q-btn color="primary" label="Ajouter Ticket" size="sm"  ></q-btn>-->
                                                <input @click="resetfile(props.row.id)" @change="uploadFiles(props.row.id)" 
                                                        type="file" name="file" :id="'filer_input'+props.row.id"  />

                                                <div :id="'defaultticket'+props.row.id" v-if="props.row.ticketimg != ''">
<!--                                                    <img style="width:80px" :src="props.row.ticketimg"/>-->
                                                    <q-img
                                                        :src="props.row.ticketimg" 
                                                        style="width:80px" 
                                                        @click="showImage(props.row.ticketimg)"
                                                        >
                                                    </q-img>
                                                    <q-btn @click="deleteImg(props.row.id)" size="xs" flat label="Supprimer"></q-btn>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="col.name=='generate'">
                                            <q-btn v-if='props.row.type=="simple" || props.row.surmesure=="1" ' size="sm" color="black"
                                                   label="Génerer Barcode" @click="generateBarcode(props.row.id)"   ></q-btn> 
                                        </div>
                                        <div v-else-if="col.name=='img'">
                                            <q-img
                                                :src="props.row.img" 
                                                style="width:30%" 
                                                >
                                            </q-img> 
                                        </div>
                                        <div v-else-if="col.name=='name'">
                                            <span>  <a :href="'/wp-admin/post.php?post='+props.row.id+'&action=edit'" target="_blank" >
                                                    {{props.row.name}} 
                                                </a> </span><br>
                                            <q-badge >
                                                {{props.row.id}}&nbsp;
                                            </q-badge>
                                            <q-badge >
                                                {{props.row.type}}&nbsp;
                                            </q-badge>
                                            <q-badge >
                                                {{props.row.sku}}
                                            </q-badge>
                                        </div>
                                        <div v-else>
                                            {{ col.value }}
                                        </div>
                                    </q-td>
                                </q-tr>
                                <q-tr v-show="props.expand" :props="props" >
                                    <q-td colspan="100%" v-if='props.row.type=="variable"' > 

                                        <q-table
                                            style="width:100%"
                                            :rows="props.row.var"
                                            row-key="id"
                                            dense
                                            hide-header
                                            :columns="cols"
                                            :rows-per-page-options="[0]"

                                            >

                                            <template v-slot:body-cell-generate="props">
                                                <q-td :props="props">
                                                    <q-btn  size="sm" color="black" label="Génerer Barcode" @click="generateBarcode('',props.row.id)"   ></q-btn> 
                                                </q-td>
                                            </template>

                                            <template v-slot:body-cell-name="props">
                                                <q-td :props="props">
                                                    <span>  <a :href="'/wp-admin/post.php?post='+props.row.id+'&action=edit'" target="_blank" >
                                                            {{props.row.name}} 
                                                        </a> </span><br>
                                                    <q-badge >
                                                        {{props.row.id}}&nbsp; 
                                                    </q-badge>
                                                    <q-badge >
                                                        {{props.row.type}}&nbsp;
                                                    </q-badge>
                                                    <q-badge >
                                                        {{props.row.sku}}
                                                    </q-badge>
                                                </q-td>
                                            </template>
                                            <template v-slot:body-cell-ticket="props">
                                                <q-td :props="props">
                                                    <div >
                                                        <!--      <q-btn color="primary" label="Ajouter Ticket" size="sm"  ></q-btn>-->
                                                        <input @click="resetfile(props.row.id)" @change="uploadFiles(props.row.id)" 
                                                                type="file" name="file" :id="'filer_input'+props.row.id"  />

                                                        <div :id="'defaultticket'+props.row.id" v-if="props.row.ticketimg != ''">
<!--                                                            <img style="width:80px" :src="props.row.ticketimg"/>-->
                                                            <q-img
                                                                :src="props.row.ticketimg" 
                                                                style="width:80px" 
                                                                @click="showImage(props.row.ticketimg)"
                                                                >
                                                            </q-img>
                                                            <q-btn @click="deleteImg(props.row.id)" size="xs" flat label="Supprimer"></q-btn>
 
                                                        </div>
                                                    </div>
                                                </q-td>
                                            </template>
                                        </q-table>
                                    </q-td>
                                </q-tr>
                            </template>




                        </q-table>


                    </q-page>
                </q-page-container>
            </q-layout>

        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.12.7/dist/quasar.umd.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.12.7/dist/lang/fr.umd.prod.js"></script>
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
            {name: 'img', label: 'Images', field: 'img', align: 'left', sortable: true},
            {name: 'name', label: 'Nom', field: 'name', align: 'left', sortable: true},
            {name: 'ticket', label: 'Ticket', field: 'ticket', align: 'left', sortable: true},
            {name: 'generate', label: 'Générer', field: 'generate', align: 'left', sortable: true},
        ])
        const $q = useQuasar()
        const filterData = ref({})
        const data = ref([])
        const cat = ref(null)
        const files = ref([])
        const cats = ref(null)
        const uploadUrl = ajaxurl + '?action=uploadTicket'
        onMounted(async () => {

            await getProductsData()
            await getcat()
        })


        async function getcat() {
            ajaxConfig.data = {action: "getCategorie"}

            ajaxConfig.success = function (resp) {
                console.log(resp)


            }

            await jQuery.ajax(ajaxConfig)
        }
        async function getProductsData() {
            $q.loading.show()
            ajaxConfig.data = {action: "get_all_products"}

            ajaxConfig.success = function (resp) {
                console.log(resp)

                data.value = resp.data
            }

            await jQuery.ajax(ajaxConfig)
            $q.loading.hide()
        }

        async function searchData() {
            await getProductsData()
        }
        async function saveInitStock(row) {
            $q.loading.show()
            ajaxConfig.data = {action: "process_custom_product", function: 'init_stock', row: row}

            ajaxConfig.success = async function (resp) {
                console.log(resp)
                row.stock = row.init_stock
                await  getProductsData()

            }

            await jQuery.ajax(ajaxConfig)
            $q.loading.hide()

        }

        async  function uploadFiles(productid) {
            $q.loading.show()

            var file_data = jQuery('#filer_input' + productid).prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('action', 'uploadTicket');
            form_data.append('productid', productid);
            ajaxConfig.dataType = 'text'  // <-- what to expect back from the PHP script, if anything
            ajaxConfig.cache = false
            ajaxConfig.contentType = false
            ajaxConfig.processData = false

            ajaxConfig.data = form_data

            ajaxConfig.success = async function (resp) {
                resp = JSON.parse(resp)
                console.log(resp)
                 getProductsData()
                     $q.loading.hide()
//                jQuery('#defaultticket' + productid).remove()
//                jQuery('#filer_input' + productid).after('<div>   <q-img  src="' + resp.file_path + '"  style="width:80px" > </q-img> </div>')
            }

            await jQuery.ajax(ajaxConfig)
            $q.loading.hide()
        }
        const resetfile = (productid) => {
            jQuery('#filer_input' + productid).val(null)
        }
        async  function generateBarcode(product_id = '0', product_var_id = '0') {
            $q.dialog({
                title: 'Barcode',
                message: ' <p>  Quantité:  <input id="ticketQty" value="1" style="width: 100%" type="number" name="qty">\n\
    </p> <p> Commencer à partir de <input id="ticketStartfrom" style="width: 100%" type="number" name="startfrom" ></p><p><label><input name="inpage" type="radio" value="2">2 par page</label> \n\
&nbsp;&nbsp;&nbsp;<label><input name="inpage" type="radio" value="4">4 par page</label>\n\
&nbsp;&nbsp;&nbsp;<label><input name="inpage" type="radio" value="6">6 par page</label></p>',
                html: true


            }).onOk(async () => {
                $q.loading.show()
                console.log(jQuery('input[name="inpage"]:checked').val())
var func = 'generate-ticket2';
if(jQuery('input[name="inpage"]:checked').val() == 4) func = 'generate-ticket4'; 
if(jQuery('input[name="inpage"]:checked').val() == 6) func = 'generate-ticket6'; 

                ajaxConfig.data = {action: "process_custom_product", function: func,
                    product_id: product_id, product_var_id: product_var_id,
                    qty: jQuery('#ticketQty').val(), startfrom: jQuery('#ticketStartfrom').val()}
                ajaxConfig.success = async function (resp) {
                    console.log(resp)
                    printJS(resp)

                }

                await jQuery.ajax(ajaxConfig)
                $q.loading.hide()
            })
        }
       async function deleteImg(product_id = '0', product_var_id = '0') {
            $q.dialog({
                title: 'Confirm',
                message: 'Confirm supprsession?',
                cancel: true,

            }).onOk(async() => {
                $q.loading.show()

                ajaxConfig.data = {action: "process_custom_product", function: 'delete-ticket',
                    product_id: product_id, product_var_id: product_var_id,
                }
                ajaxConfig.success = async function (resp) {
                    console.log(resp)

                    getProductsData()
                     $q.loading.hide()
                }

                await jQuery.ajax(ajaxConfig)
               
            })
        }
        function showImage(ticketimg) {
            $q.dialog({

                message: ' <p><img src="' + ticketimg + '" /> </p>',
                html: true


            })
        }
        return {
            deleteImg,
            showImage,
            generateBarcode,
            resetfile,
            files,
            uploadFiles,

            uploadUrl,
            cols,
            filter: ref(''),
            saveInitStock,
            data,
            searchData,
            filterData,
            date
        }
    }
})

app.use(Quasar)
Quasar.lang.set(Quasar.lang.fr)
app.mount('#q-app')
    </script>
</div>