(window.webpackJsonp=window.webpackJsonp||[]).push([[66],{"6pJc":function(a,t,s){var n=s("TLzv");"string"==typeof n&&(n=[[a.i,n,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(n,r);n.locals&&(a.exports=n.locals)},"9ch9":function(a,t,s){"use strict";s("6pJc")},Akr5:function(a,t,s){"use strict";s.r(t);var n=s("IF94"),r=s("YZfj"),o=s("1uQM"),l=s("AeMN"),e=s("oVt+"),i=s("sove"),d=s("Hrou"),c=s("C9gC"),w=s("okd0"),p=s("J2ht"),u=s("Ki4g"),b=s("bPaI"),h=s("CAmi"),m=s("SRip"),v=s("GUe+"),_=s("mwM1"),g={components:{BCard:n.a,BCardBody:r.a,BCardText:o.a,BSpinner:l.a,BRow:e.a,BCol:i.a,BTableSimple:d.a,BThead:c.a,BTbody:w.a,BTfoot:p.a,BTr:u.a,BTh:b.a,BTd:h.a,BImg:m.a,BButton:v.a,BOverlay:_.a},data:function(){return{loading:!1,isBusy:!0,table_sync:[],jumlah:0,last_sync:"",form:{user_id:"",sekolah_id:"",semester_id:""},aktif:!0}},created:function(){this.form.user_id=this.user.user_id,this.form.sekolah_id=this.user.sekolah_id,this.form.semester_id=this.user.semester.semester_id,this.loadPostsData()},methods:{loadPostsData:function(){var a=this;this.$http.post("/sinkronisasi/erapor",this.form).then((function(t){a.isBusy=!1,a.loading=!1;var s=t.data;a.last_sync=s.last_sync,a.table_sync=s.table_sync,a.jumlah=s.jumlah}))},kirimData:function(){var a=this;this.loading=!0,this.$http.post("/sinkronisasi/kirim-data",this.form).then((function(t){var s=t.data;a.$swal({icon:s.icon,title:s.title,text:s.text,customClass:{confirmButton:"btn btn-success"}}).then((function(t){a.loadPostsData()}))}))}}},f=(s("9ch9"),s("KHd+")),k=Object(f.a)(g,(function(){var a=this,t=a.$createElement,s=a._self._c||t;return s("div",[s("b-row",{staticClass:"match-height"},[s("b-col",{attrs:{cols:"6",xl:"8",md:"8",sm:"6"}},[a.isBusy?s("b-card",{staticClass:"text-center text-danger my-2"},[s("b-spinner",{staticClass:"align-middle"}),a._v(" "),s("strong",[a._v("Loading...")])],1):s("b-card",{attrs:{"no-body":""}},[s("b-card-body",{staticClass:"p-0"},[s("b-table-simple",{attrs:{bordered:""}},[s("b-tr",[s("b-td",{attrs:{rowspan:"4",width:"10%"}},[s("b-img",{staticStyle:{"max-width":"100px"},attrs:{src:"/logo.png"}})],1),a._v(" "),s("b-td",{attrs:{width:"30%"}},[a._v("NPSN Sekolah")]),a._v(" "),s("b-td",{attrs:{width:"60%"}},[s("strong",[a._v(a._s(a.user.sekolah.npsn))])])],1),a._v(" "),s("b-tr",[s("b-td",[a._v("Nama Sekolah")]),a._v(" "),s("b-td",[s("strong",[a._v(a._s(a.user.sekolah.nama))])])],1),a._v(" "),s("b-tr",[s("b-td",[a._v("Alamat Sekolah")]),a._v(" "),s("b-td",[s("strong",[a._v(a._s(a.user.sekolah.alamat))])])],1),a._v(" "),s("b-tr",[s("b-td",[a._v("Desa Kelurahan Sekolah")]),a._v(" "),s("b-td",[s("strong",[a._v(a._s(a.user.sekolah.desa_kelurahan))])])],1)],1)],1)],1)],1),a._v(" "),s("b-col",{attrs:{cols:"6",xl:"4",md:"4",sm:"6"}},[a.isBusy?s("b-card",{staticClass:"text-center text-danger my-2"},[s("b-spinner",{staticClass:"align-middle"}),a._v(" "),s("strong",[a._v("Loading...")])],1):s("b-card",{staticClass:"text-center"},[s("p",[a._v("Pengiriman data terakhir dilakukan pada "),s("br"),a._v(" "),s("strong",[a._v(a._s(a.last_sync))])]),a._v(" "),s("b-overlay",{attrs:{show:a.loading,rounded:"",opacity:"0.6",size:"sm","spinner-variant":"danger"}},[s("b-button",{attrs:{size:"lg",variant:"success",disabled:a.aktif},on:{click:a.kirimData}},[s("font-awesome-icon",{attrs:{icon:"fa-solid fa-cloud-arrow-up"}}),a._v(" "),s("strong",[a._v("KIRIM DATA")])],1)],1)],1)],1)],1),a._v(" "),a.isBusy?a._e():[s("b-card",{staticClass:"text-center",attrs:{"bg-variant":"dark"}},[s("b-card-text",{staticClass:"h2",staticStyle:{color:"#fff"}},[a._v("DATA YANG MENGALAMI PERUBAHAN")])],1),a._v(" "),s("b-card",[s("b-overlay",{attrs:{show:a.loading,rounded:"",opacity:"0.6",size:"xl","spinner-variant":"danger"}},[s("b-table-simple",{attrs:{bordered:"",responsive:""}},[s("b-thead",[s("b-tr",{attrs:{variant:"light"}},[s("b-th",{staticClass:"text-center",attrs:{width:"5%"}},[a._v("No")]),a._v(" "),s("b-th",{staticClass:"text-center",attrs:{width:"80%"}},[a._v("Data")]),a._v(" "),s("b-th",{staticClass:"text-center",attrs:{width:"15%"}},[a._v("Jml Data")])],1)],1),a._v(" "),s("b-tbody",[a._l(a.table_sync,(function(t,n){return[s("b-tr",[s("b-td",{staticClass:"text-center"},[a._v(a._s(n+1))]),a._v(" "),s("b-td",[a._v(a._s(t.data))]),a._v(" "),s("b-td",{staticClass:"text-center"},[a._v(a._s(t.count))])],1)]}))],2),a._v(" "),s("b-tfoot",[s("b-tr",{attrs:{variant:"light"}},[s("b-td",{staticClass:"text-right",attrs:{colspan:"2",variant:"light"}},[a._v("\n                JUMLAH\n              ")]),a._v(" "),s("b-td",{staticClass:"text-center",attrs:{variant:"light"}},[a._v("\n                "+a._s(a.jumlah)+"\n              ")])],1)],1)],1)],1)],1)]],2)}),[],!1,null,null,null);t.default=k.exports},TLzv:function(a,t,s){var n=s("JPst"),r=s("nm7J");(t=n(!1)).i(r),t.push([a.i,"[dir] .swal2-container .swal2-popup {\n  border-radius: 0.428rem;\n  padding-bottom: 1.5rem;\n}\n.swal2-container .swal2-popup .swal2-close:focus {\n  outline: none;\n}\n[dir] .swal2-container .swal2-popup .swal2-close:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup .swal2-content {\n  color: #6e6b7b;\n}\n.swal2-container .swal2-popup .swal2-title {\n  font-size: 1.35rem;\n  font-weight: inherit;\n  color: #5e5873;\n}\n[dir] .swal2-container .swal2-popup button.swal2-confirm:focus {\n  box-shadow: none;\n}\n.swal2-container .swal2-popup input.swal2-input {\n  height: 2.714rem;\n  font-size: 0.9rem;\n  color: #6e6b7b;\n  line-height: 1.45;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input {\n  padding: 0.438rem 1rem;\n  border-color: #d8d6de;\n  border-radius: 0.357rem;\n}\n[dir] .swal2-container .swal2-popup input.swal2-input:focus {\n  border-color: #7367f0 !important;\n  box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);\n}\n.swal2-container .swal2-popup .swal2-select {\n  width: 100%;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step {\n  background-color: #7367f0;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step-line, [dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step ~ .swal2-progress-step {\n  background-color: #b3adf7;\n}\n[dir] .swal2-container .swal2-popup .swal2-progress-steps .swal2-progress-step-line {\n  background-color: #7367f0;\n}\n.swal2-container .swal2-popup pre {\n  min-height: 1.35rem;\n}\n[dir] .swal2-container .swal2-popup pre {\n  margin-top: 0.5rem;\n}\n.swal2-container .swal2-popup .swal2-footer {\n  color: #6e6b7b;\n}\n[dir] .swal2-container .swal2-popup .swal2-footer {\n  border-color: #ebe9f1;\n}\n.dark-layout .swal2-container .swal2-popup .swal2-title {\n  color: #d0d2d6;\n}\n[dir] .dark-layout .swal2-container .swal2-modal {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-title {\n  color: #d0d2d6;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-header .swal2-icon-text {\n  color: inherit;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-left, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-circular-line-right, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-success-fix, [dir] .dark-layout .swal2-container .swal2-modal .swal2-header .swal2-animate-success-icon {\n  background-color: #283046 !important;\n}\n.dark-layout .swal2-container .swal2-modal input.swal2-input {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal input.swal2-input {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-content, .dark-layout .swal2-container .swal2-modal .swal2-html-container {\n  color: #b4b7bd;\n}\n.dark-layout .swal2-container .swal2-modal pre, .dark-layout .swal2-container .swal2-modal code {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-input {\n  background-color: #283046;\n}\n.dark-layout .swal2-container .swal2-modal .swal2-footer {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-modal .swal2-footer {\n  border-color: #3b4253;\n}\n.dark-layout .swal2-container .swal2-validation-message {\n  color: #b4b7bd;\n}\n[dir] .dark-layout .swal2-container .swal2-validation-message {\n  background-color: #161d31;\n}\nhtml[dir=rtl] .swal2-rtl .swal2-close {\n  left: 0;\n  right: auto;\n}",""]),a.exports=t}}]);