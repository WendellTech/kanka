var J={grad:.9,turn:360,rad:360/(2*Math.PI)},d=function(n){return typeof n=="string"?n.length>0:typeof n=="number"},i=function(n,t,e){return t===void 0&&(t=0),e===void 0&&(e=Math.pow(10,t)),Math.round(e*n)/e+0},h=function(n,t,e){return t===void 0&&(t=0),e===void 0&&(e=1),n>e?e:n>t?n:t},P=function(n){return(n=isFinite(n)?n%360:0)>0?n:n+360},k=function(n){return{r:h(n.r,0,255),g:h(n.g,0,255),b:h(n.b,0,255),a:h(n.a)}},v=function(n){return{r:i(n.r),g:i(n.g),b:i(n.b),a:i(n.a,3)}},R=/^#([0-9a-f]{3,8})$/i,f=function(n){var t=n.toString(16);return t.length<2?"0"+t:t},D=function(n){var t=n.r,e=n.g,r=n.b,o=n.a,s=Math.max(t,e,r),u=s-Math.min(t,e,r),l=u?s===t?(e-r)/u:s===e?2+(r-t)/u:4+(t-e)/u:0;return{h:60*(l<0?l+6:l),s:s?u/s*100:0,v:s/255*100,a:o}},F=function(n){var t=n.h,e=n.s,r=n.v,o=n.a;t=t/360*6,e/=100,r/=100;var s=Math.floor(t),u=r*(1-e),l=r*(1-(t-s)*e),p=r*(1-(1-t+s)*e),g=s%6;return{r:255*[r,l,u,u,p,r][g],g:255*[p,r,r,l,u,u][g],b:255*[u,u,p,r,r,l][g],a:o}},S=function(n){return{h:P(n.h),s:h(n.s,0,100),l:h(n.l,0,100),a:h(n.a)}},I=function(n){return{h:i(n.h),s:i(n.s),l:i(n.l),a:i(n.a,3)}},w=function(n){return F((e=(t=n).s,{h:t.h,s:(e*=((r=t.l)<50?r:100-r)/100)>0?2*e/(r+e)*100:0,v:r+e,a:t.a}));var t,e,r},b=function(n){return{h:(t=D(n)).h,s:(o=(200-(e=t.s))*(r=t.v)/100)>0&&o<200?e*r/100/(o<=100?o:200-o)*100:0,l:o/2,a:t.a};var t,e,r,o},q=/^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s*,\s*([+-]?\d*\.?\d+)%\s*,\s*([+-]?\d*\.?\d+)%\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,A=/^hsla?\(\s*([+-]?\d*\.?\d+)(deg|rad|grad|turn)?\s+([+-]?\d*\.?\d+)%\s+([+-]?\d*\.?\d+)%\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,L=/^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*,\s*([+-]?\d*\.?\d+)(%)?\s*(?:,\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,T=/^rgba?\(\s*([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s+([+-]?\d*\.?\d+)(%)?\s*(?:\/\s*([+-]?\d*\.?\d+)(%)?\s*)?\)$/i,M={string:[[function(n){var t=R.exec(n);return t?(n=t[1]).length<=4?{r:parseInt(n[0]+n[0],16),g:parseInt(n[1]+n[1],16),b:parseInt(n[2]+n[2],16),a:n.length===4?i(parseInt(n[3]+n[3],16)/255,2):1}:n.length===6||n.length===8?{r:parseInt(n.substr(0,2),16),g:parseInt(n.substr(2,2),16),b:parseInt(n.substr(4,2),16),a:n.length===8?i(parseInt(n.substr(6,2),16)/255,2):1}:null:null},"hex"],[function(n){var t=L.exec(n)||T.exec(n);return t?t[2]!==t[4]||t[4]!==t[6]?null:k({r:Number(t[1])/(t[2]?100/255:1),g:Number(t[3])/(t[4]?100/255:1),b:Number(t[5])/(t[6]?100/255:1),a:t[7]===void 0?1:Number(t[7])/(t[8]?100:1)}):null},"rgb"],[function(n){var t=q.exec(n)||A.exec(n);if(!t)return null;var e,r,o=S({h:(e=t[1],r=t[2],r===void 0&&(r="deg"),Number(e)*(J[r]||1)),s:Number(t[3]),l:Number(t[4]),a:t[5]===void 0?1:Number(t[5])/(t[6]?100:1)});return w(o)},"hsl"]],object:[[function(n){var t=n.r,e=n.g,r=n.b,o=n.a,s=o===void 0?1:o;return d(t)&&d(e)&&d(r)?k({r:Number(t),g:Number(e),b:Number(r),a:Number(s)}):null},"rgb"],[function(n){var t=n.h,e=n.s,r=n.l,o=n.a,s=o===void 0?1:o;if(!d(t)||!d(e)||!d(r))return null;var u=S({h:Number(t),s:Number(e),l:Number(r),a:Number(s)});return w(u)},"hsl"],[function(n){var t=n.h,e=n.s,r=n.v,o=n.a,s=o===void 0?1:o;if(!d(t)||!d(e)||!d(r))return null;var u=function(l){return{h:P(l.h),s:h(l.s,0,100),v:h(l.v,0,100),a:h(l.a)}}({h:Number(t),s:Number(e),v:Number(r),a:Number(s)});return F(u)},"hsv"]]},j=function(n,t){for(var e=0;e<t.length;e++){var r=t[e][0](n);if(r)return[r,t[e][1]]}return[null,void 0]},V=function(n){return typeof n=="string"?j(n.trim(),M.string):typeof n=="object"&&n!==null?j(n,M.object):[null,void 0]},m=function(n,t){var e=b(n);return{h:e.h,s:h(e.s+100*t,0,100),l:e.l,a:e.a}},y=function(n){return(299*n.r+587*n.g+114*n.b)/1e3/255},C=function(n,t){var e=b(n);return{h:e.h,s:e.s,l:h(e.l+100*t,0,100),a:e.a}},E=function(){function n(t){this.parsed=V(t)[0],this.rgba=this.parsed||{r:0,g:0,b:0,a:1}}return n.prototype.isValid=function(){return this.parsed!==null},n.prototype.brightness=function(){return i(y(this.rgba),2)},n.prototype.isDark=function(){return y(this.rgba)<.5},n.prototype.isLight=function(){return y(this.rgba)>=.5},n.prototype.toHex=function(){return t=v(this.rgba),e=t.r,r=t.g,o=t.b,u=(s=t.a)<1?f(i(255*s)):"","#"+f(e)+f(r)+f(o)+u;var t,e,r,o,s,u},n.prototype.toRgb=function(){return v(this.rgba)},n.prototype.toRgbString=function(){return t=v(this.rgba),e=t.r,r=t.g,o=t.b,(s=t.a)<1?"rgba("+e+", "+r+", "+o+", "+s+")":"rgb("+e+", "+r+", "+o+")";var t,e,r,o,s},n.prototype.toHsl=function(){return I(b(this.rgba))},n.prototype.toHslString=function(){return t=I(b(this.rgba)),e=t.h,r=t.s,o=t.l,(s=t.a)<1?"hsla("+e+", "+r+"%, "+o+"%, "+s+")":"hsl("+e+", "+r+"%, "+o+"%)";var t,e,r,o,s},n.prototype.toHsv=function(){return t=D(this.rgba),{h:i(t.h),s:i(t.s),v:i(t.v),a:i(t.a,3)};var t},n.prototype.invert=function(){return a({r:255-(t=this.rgba).r,g:255-t.g,b:255-t.b,a:t.a});var t},n.prototype.saturate=function(t){return t===void 0&&(t=.1),a(m(this.rgba,t))},n.prototype.desaturate=function(t){return t===void 0&&(t=.1),a(m(this.rgba,-t))},n.prototype.grayscale=function(){return a(m(this.rgba,-1))},n.prototype.lighten=function(t){return t===void 0&&(t=.1),a(C(this.rgba,t))},n.prototype.darken=function(t){return t===void 0&&(t=.1),a(C(this.rgba,-t))},n.prototype.rotate=function(t){return t===void 0&&(t=15),this.hue(this.hue()+t)},n.prototype.alpha=function(t){return typeof t=="number"?a({r:(e=this.rgba).r,g:e.g,b:e.b,a:t}):i(this.rgba.a,3);var e},n.prototype.hue=function(t){var e=b(this.rgba);return typeof t=="number"?a({h:t,s:e.s,l:e.l,a:e.a}):i(e.h)},n.prototype.isEqual=function(t){return this.toHex()===a(t).toHex()},n}(),a=function(n){return n instanceof E?n:new E(n)};let H,x={};$(document).ready(function(){z()});const z=()=>{B(),$.each($(".picker"),function(){let n=$(this).css("backgroundColor"),t=$(this).data("target");$(this).spectrum({preferredFormat:"hsl",showInput:!1,color:n,change:function(e){G(e,t)},show:function(){},hide:function(){}})}),$("#theme-builder").on("submit",function(){return $("#form-submit-main").addClass("loading").prop("disabled",!0),H.val(JSON.stringify(x)),!0})},B=()=>{H=$("#field-theme");let n=H.val();if(!n){console.log("no config");return}let t=JSON.parse(n);Object.entries(t).forEach(([e,r])=>{x[e]=r})},G=(n,t)=>{let e=n.toHslString().replace("hsl(","").replaceAll(",","").replace(")",""),r=N(n.toHslString()).toHsl(),o=O(n.toHslString()).toHsl();if(t==="a"||t==="s"||t==="p"||t==="n")c(t,e),c(t+"f",r.h+" "+r.s+"% "+r.l+"%"),c(t+"c",o.h+" "+o.s+"% "+o.l+"%");else if(t==="in"||t==="su"||t==="wa"||t==="er")c(t,e),c(t+"c",o.h+" "+o.s+"% "+o.l+"%");else if(t==="b"){let s=N(n.toHslString(),.1).toHsl(),u=N(n.toHslString(),.2).toHsl();c(t+"1",e),c(t+"2",s.h+" "+s.s+"% "+s.l+"%"),c(t+"3",u.h+" "+u.s+"% "+u.l+"%"),c(t+"c",o.h+" "+o.s+"% "+o.l+"%")}else if(t==="w"){let s=O(n.toHslString());c("content-wrapper-background","#"+n.toHex()),c("theme-main-text",""+s.toHex())}},c=(n,t)=>{x[n]=t,document.documentElement.style.setProperty("--"+n,t)},O=(n,t=.8)=>a(n).isDark()?a(n).lighten(t):a(n).darken(t),N=(n,t=.2)=>a(n).darken(t);
