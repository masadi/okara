(window.webpackJsonp=window.webpackJsonp||[]).push([[56],{OOfG:function(t,n,e){"use strict";var a={components:{BImg:e("SRip").a},data:function(){return{sideImg:"/images/logo/logo.png"}},computed:{imgUrl:function(){return this.sideImg}}},i=e("KHd+"),r=Object(i.a)(a,(function(){var t=this.$createElement;return(this._self._c||t)("b-img",{attrs:{src:this.imgUrl,height:"24",alt:"Logo"}})}),[],!1,null,null,null);n.a=r.exports},QfnW:function(t,n,e){(n=e("JPst")(!1)).push([t.i,".misc-wrapper {\n  display: flex;\n  flex-basis: 100%;\n  min-height: 100vh;\n  width: 100%;\n  align-items: center;\n  justify-content: center;\n}\n.misc-wrapper .misc-inner {\n  position: relative;\n  max-width: 750px;\n}\n.misc-wrapper .brand-logo {\n  display: flex;\n  justify-content: center;\n  position: absolute;\n  top: 2rem;\n}\n[dir] .misc-wrapper .brand-logo {\n  margin: 0;\n}\n[dir=ltr] .misc-wrapper .brand-logo {\n  left: 2rem;\n}\n[dir=rtl] .misc-wrapper .brand-logo {\n  right: 2rem;\n}\n.misc-wrapper .brand-logo .brand-text {\n  font-weight: 600;\n}\n@media (max-height: 625px) {\n[dir] .misc-wrapper .misc-inner {\n    margin-top: 4rem;\n}\n}\n.brand-logo svg {\n  height: 28px;\n  width: 40.95px;\n}",""]),t.exports=n},SRip:function(t,n,e){"use strict";e.d(n,"b",(function(){return d})),e.d(n,"a",(function(){return f}));var a=e("L3ns"),i=e("tC49"),r=e("xjcK"),s=e("pyNs"),c=e("Iyau"),l=e("bAY6"),o=e("ex6f"),u=e("OljW"),p=e("z3V6"),g=e("+nMp");function b(t,n,e){return n in t?Object.defineProperty(t,n,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[n]=e,t}var m='<svg width="%{w}" height="%{h}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %{w} %{h}" preserveAspectRatio="none"><rect width="100%" height="100%" style="fill:%{f};"></rect></svg>',d=Object(p.d)({alt:Object(p.c)(s.u),blank:Object(p.c)(s.g,!1),blankColor:Object(p.c)(s.u,"transparent"),block:Object(p.c)(s.g,!1),center:Object(p.c)(s.g,!1),fluid:Object(p.c)(s.g,!1),fluidGrow:Object(p.c)(s.g,!1),height:Object(p.c)(s.p),left:Object(p.c)(s.g,!1),right:Object(p.c)(s.g,!1),rounded:Object(p.c)(s.j,!1),sizes:Object(p.c)(s.f),src:Object(p.c)(s.u),srcset:Object(p.c)(s.f),thumbnail:Object(p.c)(s.g,!1),width:Object(p.c)(s.p)},r.S),f=Object(a.c)({name:r.S,functional:!0,props:d,render:function(t,n){var e,a=n.props,r=n.data,s=a.alt,p=a.src,d=a.block,f=a.fluidGrow,h=a.rounded,v=Object(u.b)(a.width)||null,w=Object(u.b)(a.height)||null,j=null,O=Object(c.b)(a.srcset).filter(l.a).join(","),x=Object(c.b)(a.sizes).filter(l.a).join(",");return a.blank&&(!w&&v?w=v:!v&&w&&(v=w),v||w||(v=1,w=1),p=function(t,n,e){var a=encodeURIComponent(m.replace("%{w}",Object(g.g)(t)).replace("%{h}",Object(g.g)(n)).replace("%{f}",e));return"data:image/svg+xml;charset=UTF-8,".concat(a)}(v,w,a.blankColor||"transparent"),O=null,x=null),a.left?j="float-left":a.right?j="float-right":a.center&&(j="mx-auto",d=!0),t("img",Object(i.a)(r,{attrs:{src:p,alt:s,width:v?Object(g.g)(v):null,height:w?Object(g.g)(w):null,srcset:O||null,sizes:x||null},class:(e={"img-thumbnail":a.thumbnail,"img-fluid":a.fluid||f,"w-100":f,rounded:""===h||!0===h},b(e,"rounded-".concat(h),Object(o.n)(h)&&""!==h),b(e,j,j),b(e,"d-block",d),e)}))}})},dfK2:function(t,n,e){"use strict";e.r(n);var a=e("qlm0"),i=e("SRip"),r=e("GUe+"),s=e("OOfG"),c=e("tvh2"),l=e("s7eH"),o={components:{BLink:a.a,BImg:i.a,BButton:r.a,VuexyLogo:s.a},data:function(){return{downImg:"/images/pages/not-authorized.svg",app:c.a.state.appConfig.app}},computed:{imgUrl:function(){return"dark"===c.a.state.appConfig.layout.skin?(this.downImg="/images/pages/not-authorized-dark.svg",this.downImg):this.downImg}},methods:{loginRoute:function(){var t=JSON.parse(localStorage.getItem("userData"));return Object(l.a)(t?t.role:null)}}},u=(e("qfbE"),e("KHd+")),p=Object(u.a)(o,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"misc-wrapper"},[e("b-link",{staticClass:"brand-logo"},[e("vuexy-logo"),t._v(" "),e("h2",{staticClass:"brand-text text-primary ml-1"},[t._v("\n      "+t._s(t.app.name)+" Versi "+t._s(t.app.version)+"\n    ")])],1),t._v(" "),e("div",{staticClass:"misc-inner p-2 p-sm-3"},[e("div",{staticClass:"w-100 text-center"},[e("h2",{staticClass:"mb-1"},[t._v("\n        Akses Terbatas! 🔐\n      ")]),t._v(" "),e("p",{staticClass:"mb-2"},[t._v("\n        Anda tidak diizinkan untuk mengakses laman ini.!!\n      ")]),t._v(" "),e("b-button",{staticClass:"mb-1 btn-sm-block",attrs:{variant:"primary",to:t.loginRoute()}},[t._v("Kembali ke Laman Utama")]),t._v(" "),e("b-img",{attrs:{fluid:"",src:t.imgUrl,alt:"Not authorized page"}})],1)])],1)}),[],!1,null,null,null);n.default=p.exports},kUDI:function(t,n,e){var a=e("QfnW");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(a,i);a.locals&&(t.exports=a.locals)},qfbE:function(t,n,e){"use strict";e("kUDI")}}]);