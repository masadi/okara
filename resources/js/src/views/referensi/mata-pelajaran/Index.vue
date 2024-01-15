<template>
  <b-card>
    <datatable :isBusy="isBusy" :items="items" :fields="fields" :meta="meta" @per_page="handlePerPage" @pagination="handlePagination" @search="handleSearch" @sort="handleSort" @edit="handleEdit" @pembelajaran="handlePembelajaran" />
    <b-modal ref="edit-modal" size="lg" :title="title" @hidden="resetModal" @ok="handleUpdate">
      <b-row>
          <b-col cols="12">
            <b-form-group label="Nama Mata Pelajaran" label-for="id" label-cols-md="3" :invalid-feedback="feedback.nama" :state="state.nama">
              <b-form-input v-model="form.nama" :state="state.nama"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
    </b-modal>
    <b-modal ref="pembelajaran-modal" size="xl" :title="title" @hidden="resetModal" @ok="handleOk">
      <b-table-simple hover bordered responsive class="pb-5">
        <b-thead>
          <b-tr>
            <b-th class="text-center" width="30%">Kelas</b-th>
            <b-th class="text-center" width="30%">Guru Mapel</b-th>
            <b-th class="text-center" width="30%">Kelompok</b-th>
            <b-th class="text-center" width="10%">No Urut</b-th>
          </b-tr>
        </b-thead>
        <b-tbody>
          <template v-for="formulir in jumlah_form">
            <b-tr>
              <b-td>
                <b-form-group :invalid-feedback="feedback.rombongan_belajar_id[formulir]" :state="state.rombongan_belajar_id[formulir]">
                  <v-select v-model="form.rombongan_belajar_id[formulir]" :reduce="nama => nama.rombongan_belajar_id" label="nama" :options="data_rombel" placeholder="== Pilih Kelas ==" :state="state.rombongan_belajar_id[formulir]">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.guru_id[formulir]" :state="state.guru_id[formulir]">
                  <v-select v-model="form.guru_id[formulir]" :reduce="nama_lengkap => nama_lengkap.guru_id" label="nama_lengkap" :options="data_guru" placeholder="== Pilih Guru ==" :state="state.guru_id[formulir]">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.kelompok_id[formulir]" :state="state.kelompok_id[formulir]">
                  <v-select v-model="form.kelompok_id[formulir]" :reduce="nama_kelompok => nama_kelompok.kelompok_id" label="nama_kelompok" :options="data_kelompok" placeholder="== Pilih Kelompok ==" :state="state.kelompok_id[formulir]">
                    <template #no-options="{ search, searching, loading }">
                      Tidak ada data untuk ditampilkan
                    </template>
                  </v-select>
                </b-form-group>
              </b-td>
              <b-td>
                <b-form-group :invalid-feedback="feedback.no_urut[formulir]" :state="state.no_urut[formulir]">
                  <b-form-input v-model="form.no_urut[formulir]" :state="state.no_urut[formulir]"></b-form-input>
                </b-form-group>
              </b-td>
            </b-tr>
          </template>
        </b-tbody>
      </b-table-simple>
      <template #modal-footer="{ ok, cancel }">
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="danger" class="d-inline-block">
          <b-button variant="danger" @click="addForm">Tambah Form</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="secondary" class="d-inline-block">
          <b-button @click="cancel()">Tutup</b-button>
        </b-overlay>
        <b-overlay :show="loading_form" rounded opacity="0.6" spinner-small spinner-variant="success" class="d-inline-block">
          <b-button variant="success" @click="ok()">Simpan</b-button>
        </b-overlay>
      </template>
    </b-modal>
  </b-card>
</template>

<script>
import { BCard, BButton, BOverlay, BFormGroup, BFormInput, BRow, BCol, BTableSimple, BThead, BTbody, BTh, BTr, BTd } from 'bootstrap-vue'
import vSelect from 'vue-select'
import Datatable from './Datatable.vue' //IMPORT COMPONENT DATATABLENYA
export default {
  components: {
    BCard,
    BButton,
    BOverlay,
    BFormGroup,
    BFormInput,
    BRow, 
    BCol,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd,
    vSelect,
    Datatable
  },
  data() {
    return {
      loading_form: false,
      title: '',
      isBusy: true,
      fields: [
        {
          key: 'mata_pelajaran_id',
          label: 'ID',
          sortable: true,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
        {
          key: 'nama',
          label: 'Nama Mata Pelajaran',
          sortable: true,
          thClass: 'text-center',
        },
        {
          key: 'action',
          label: 'Aksi',
          sortable: false,
          thClass: 'text-center',
          tdClass: 'text-center',
        },
      ],
      items: [],
      meta: {},
      current_page: 1, 
      per_page: 10,
      search: '',
      sortBy: 'mata_pelajaran_id',
      sortByDesc: false,
      jumlah_form: 1,
      data_rombel: [],
      data_guru: [],
      data_kelompok: [],
      form: {
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        mata_pelajaran_id: '',
        nama: '',
        rombongan_belajar_id: {},
        guru_id: {},
        kelompok_id: {},
        no_urut: {},
      },
      feedback: {
        nama: '',
        rombongan_belajar_id: {},
        guru_id: {},
        kelompok_id: {},
        no_urut: {},
      },
      state: {
        nama: null,
        rombongan_belajar_id: {},
        guru_id: {},
        kelompok_id: {},
        no_urut: {},
      },
    }
  },
  created() {
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
  },
  methods: {
    loadPostsData() {
      this.isBusy = true
      //let current_page = this.search == '' ? this.current_page : this.current_page != 1 ? 1 : this.current_page
      let current_page = this.current_page//this.search == '' ? this.current_page : 1
      //LAKUKAN REQUEST KE API UNTUK MENGAMBIL DATA POSTINGAN
      this.$http.get('/referensi/mata-pelajaran', {
        params: {
          user_id: this.user.user_id,
          sekolah_id: this.user.sekolah_id,
          semester_id: this.user.semester.semester_id,
          periode_aktif: this.user.semester.nama,
          page: current_page,
          per_page: this.per_page,
          q: this.search,
          sortby: this.sortBy,
          sortbydesc: this.sortByDesc ? 'DESC' : 'ASC'
        }
      }).then(response => {
        //this.items = response.data.all_pd
        let getData = response.data.data
        this.isBusy = false
        this.items = getData.data//MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
        //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
        this.meta = {
          total: getData.total,
          current_page: getData.current_page,
          per_page: getData.per_page,
          from: getData.from,
          to: getData.to,
        }
      })
    },
    //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
    handlePerPage(val) {
      this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
      this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
    },
    //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
    handlePagination(val) {
      this.current_page = val //SET CURRENT PAGE YANG AKTIF
      this.loadPostsData()
    },
    //JIKA ADA DATA PENCARIAN
    handleSearch(val) {
      this.search = val //SET VALUE PENCARIAN KE VARIABLE SEARCH
      this.loadPostsData() //REQUEST DATA BARU
    },
    //JIKA ADA EMIT SORT
    handleSort(val) {
      if (val.sortBy) {
        this.sortBy = val.sortBy
        this.sortByDesc = val.sortDesc
        this.loadPostsData() //DAN LOAD DATA BARU BERDASARKAN SORT
      }
    },
    handleEdit(val){
      console.log('handleEdit');
      console.log(val);
      this.form.mata_pelajaran_id = val.mata_pelajaran_id
      this.form.nama = val.nama
      this.$refs['edit-modal'].show()
    },
    handlePembelajaran(val){
      console.log('handlePembelajaran');
      console.log(val);
      this.form.mata_pelajaran_id = val.mata_pelajaran_id
      this.form.nama = val.nama
      this.$http.post('/rombongan-belajar/get-pembelajaran', this.form).then(response => {
        let getData = response.data
        this.jumlah_form = getData.pembelajaran.length + 1
        this.data_rombel = getData.rombel
        this.data_guru = getData.guru
        this.data_kelompok = getData.kelompok
        var _this = this
        getData.pembelajaran.forEach(function(value, key) {
          var urut = key + 1;
          _this.form.rombongan_belajar_id[urut] = value.rombongan_belajar_id
          _this.form.guru_id[urut] = value.guru_id
          _this.form.kelompok_id[urut] = value.kelompok_id
          _this.form.no_urut[urut] = value.no_urut
        })
        this.$refs['pembelajaran-modal'].show()
      }).catch(error => {
        console.log(error);
      })
    },
    getkelompok(val){
      console.log(val);
    },
    handleUpdate(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.updateData()
    },
    updateData(){
      this.$http.post('/referensi/update-mapel', this.form).then(response => {
        this.alert(response.data, true)
      }).catch(error => {
        console.log(error);
      })
    },
    handleOk(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.handleSubmit()
    },
    handleSubmit() {
      this.$http.post('/rombongan-belajar/insert-pembelajaran', this.form).then(response => {
        this.alert(response.data, false)
      }).catch(error => {
        console.log(error);
      })
    },
    alert(getData, refresh){
      this.$swal({
        icon: getData.icon,
        title: getData.title,
        text: getData.text,
        customClass: {
          confirmButton: 'btn btn-success',
        },
        allowOutsideClick: false,
      }).then(result => {
        if(refresh){
          this.$refs['edit-modal'].hide()
          this.loadPostsData()
        } else {
          this.$refs['pembelajaran-modal'].hide()
        }      
      })
    },
    resetModal(){
      //
    },
    addForm(){
      this.jumlah_form = this.jumlah_form + 1
      //this.form.status[this.jumlah_form] = null
    },
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>