<template>
  <b-card no-body>
    <b-overlay :show="loadingForm" rounded opacity="0.6" size="lg" spinner-variant="danger">
      <b-card-body>
        <div v-if="isBusy" class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
        <div v-else>
          <template v-if="hasRole('waka') || hasRole('tu')">
            <formulir-waka @rombel="handleRombel" @hide_form="handleHideForm" :meta="meta" :form="form"></formulir-waka>
          </template>
          <template v-if="show_table">
            <b-row class="mb-2" v-if="hasRole('waka') || hasRole('tu')">
              <b-col cols="12">
                <b-form-group label="Unduh Legger" label-for="unduh" label-cols-md="3">
                  <b-button variant="success" :href="legger_link" target="_blank">Unduh Legger</b-button>
                </b-form-group>
              </b-col>
            </b-row>
            <b-table-simple bordered striped responsive>
              <b-thead>
                <b-tr>
                  <b-th class="text-center">Nama Peserta Didik</b-th>
                  <b-th class="text-center">NISN</b-th>
                  <template v-for="(pembelajaran, index) in data_pembelajaran">
                    <b-th class="text-center">{{pembelajaran.nama_mata_pelajaran}}</b-th>
                  </template>
                </b-tr>
              </b-thead>
              <b-tbody>
                <template v-for="(item, index) in data_siswa">
                  <b-tr>
                    <b-td>{{item.nama}}</b-td>
                    <b-td class="text-center">{{item.nisn}}</b-td>
                    <template v-for="(pembelajaran, index) in data_pembelajaran">
                      <template v-if="pembelajaran.rombongan_belajar.jenis_rombel == '1'">
                        <b-td class="text-center" v-if="merdeka">{{getNilai(pembelajaran.all_nilai_akhir_kurmer, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                        <b-td class="text-center" v-else>{{getNilai(pembelajaran.all_nilai_akhir_pengetahuan, item.anggota_rombel.anggota_rombel_id)}}</b-td>
                      </template>
                      <template v-else>
                        <b-td class="text-center">{{getNilai(pembelajaran.all_nilai_akhir_kurmer, item.anggota_pilihan.anggota_rombel_id)}}</b-td>
                      </template>
                    </template>
                  </b-tr>
                </template>
              </b-tbody>
            </b-table-simple>
          </template>
        </div>
      </b-card-body>
    </b-overlay>
  </b-card>
</template>

<script>
import { BCard, BCardBody, BSpinner, BOverlay, BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup } from 'bootstrap-vue'
import FormulirWaka from './../components/FormulirWaka.vue'
import eventBus from '@core/utils/eventBus';
export default {
  components: {
    BCard,
    BCardBody,
    BSpinner,
    BOverlay,
    BTableSimple, BThead, BTbody, BTh, BTr, BTd, BRow, BCol, BButton, BFormGroup,
    FormulirWaka
  },
  data() {
    return {
      disabled: false,
      show_table: false,
      show_form: false,
      loadingForm: false,
      isBusy: true,
      merdeka: false,
      form: {
        aksi: 'unduh-legger',
        user_id: '',
        guru_id: '',
        sekolah_id: '',
        semester_id: '',
        periode_aktif: '',
        tingkat: '',
        rombongan_belajar_id: '',
      },
      meta: {
        tingkat_feedback: '',
        tingkat_state: null,
        rombongan_belajar_id_feedback: '',
        rombongan_belajar_id_state: '',
      },
      rombongan_belajar_id: '',
      data_siswa: [],
      data_pembelajaran: [],
      legger_link: '',
    }
  },
  created() {
    this.form.user_id = this.user.user_id
    this.form.guru_id = this.user.guru_id
    this.form.sekolah_id = this.user.sekolah_id
    this.form.semester_id = this.user.semester.semester_id
    this.form.periode_aktif = this.user.semester.nama
    this.loadPostsData()
    eventBus.$on('unduhLegger', this.handleEvent);
  },
  methods: {
    handleEvent(){
      if(this.rombongan_belajar_id){
      //http://erapor6.test/unduh/leger-nilai-kurmer/3741076a-c0ca-4c0f-bbba-71a84eca9ef9
        var url = `/downloads/leger-nilai-kurmer/${this.rombongan_belajar_id}/${this.form.sekolah_id}/${this.form.semester_id}`
        console.log(url);
        window.open(url, '_blank').focus();
      }
    },
    loadPostsData(){
      this.$http.post('/walas/unduh-legger', this.form).then(response => {
        this.isBusy = false
        let getData = response.data
        this.show_table = getData.show
        this.show_form = getData.form
        if(getData.rombel){
          this.rombongan_belajar_id = getData.rombel.rombongan_belajar_id
        } else {
          eventBus.$emit('hide-button');
        }
        this.data_siswa = getData.data_siswa
        this.data_pembelajaran = getData.pembelajaran
        this.merdeka = getData.merdeka
        
      })
    },
    getNilai(arr, anggota_rombel_id){
      var nilai_akhir = '-';
      var nilai = arr.filter(function (el) {
        return el.anggota_rombel_id == anggota_rombel_id
      });
      if(nilai.length){
        nilai_akhir = nilai[0].nilai
      }
      return nilai_akhir;
    },
    handleHideForm(){
      console.log('hide_form');
      this.show_table = false
      this.data_siswa = []
    },
    handleRombel(val){
      console.log(val);
      this.loadingForm = true
      this.$http.post('/waka/unduh-legger', this.form).then(response => {
        this.loadingForm = false
        let getData = response.data
        this.show_table = getData.show
        this.data_siswa = getData.data_siswa
        this.merdeka = getData.merdeka
        this.data_pembelajaran = getData.pembelajaran
        var url = `/downloads/leger-nilai-kurmer/${this.form.rombongan_belajar_id}/${this.form.sekolah_id}/${this.form.semester_id}`
        this.legger_link = url
      })
    }
  },
}
</script>
<style lang="scss">
@import '~@resources/scss/vue/libs/vue-sweetalert.scss';
</style>