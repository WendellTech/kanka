(()=>{var t={4684:()=>{function t(t,r){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var r=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null==r)return;var n,a,i=[],o=!0,l=!1;try{for(r=r.call(t);!(o=(n=r.next()).done)&&(i.push(n.value),!e||i.length!==e);o=!0);}catch(t){l=!0,a=t}finally{try{o||null==r.return||r.return()}finally{if(l)throw a}}return i}(t,r)||function(t,r){if(!t)return;if("string"==typeof t)return e(t,r);var n=Object.prototype.toString.call(t).slice(8,-1);"Object"===n&&t.constructor&&(n=t.constructor.name);if("Map"===n||"Set"===n)return Array.from(t);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return e(t,r)}(t,r)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function e(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}var r,n,a;function i(e){n.each((function(){$(this).removeClass("vote-selected")}));var i={vote:e};a=e,$.post(r.val(),i).done((function(r){e&&$(".vote-body[data-option='"+a+"']").addClass("vote-selected"),r.data&&function(e){for(var r=0,n=Object.entries(e);r<n.length;r++){var a=t(n[r],2),i=a[0],o=a[1];$(".vote-progress[data-width='"+i+"']").width(o+"%"),$(".vote-result[data-result='"+i+"']").html(o+"%")}}(r.data)})).fail((function(){}))}$(document).ready((function(){!function(){if(0===(r=$("#community-vote-url")).length)return;$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),(n=$(".vote-body")).click((function(){var t=$(this).data("option");$(this).hasClass("vote-selected")?i():i(t)}))}()}))}},e={};function r(n){var a=e[n];if(void 0!==a)return a.exports;var i=e[n]={exports:{}};return t[n](i,i.exports,r),i.exports}(()=>{function t(){$('[data-pricing="monthly"]').removeClass("text-bold"),$('[data-pricing="yearly"]').addClass("text-bold"),$('[data-pricing="toggle"]').removeClass("pricing-monthly").addClass("pricing-yearly"),$("div.pricing").removeClass("pricing-monthly").addClass("pricing-yearly")}function e(){$('[data-pricing="monthly"]').addClass("text-bold"),$('[data-pricing="yearly"]').removeClass("text-bold"),$('[data-pricing="toggle"]').removeClass("pricing-yearly").addClass("pricing-monthly"),$("div.pricing").removeClass("pricing-yearly").addClass("pricing-monthly")}$(document).ready((function(r){$(".youtube-placeholder").length&&$(".youtube-placeholder").on("click",(function(){var t='<div class="embed-responsive embed-responsive-16by9"><div class="youtube-video embed-responsive-item" data-src="'+$(this).data("yt-url")+'"><iframe class="embed-responsive-item" src="'+$(this).data("yt-url")+'" data-src="'+$(this).data("yt-url")+'" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div></div>';$(this).hide().after(t)})),function(){if(0===$(".faq-categories").length)return;var t=window.location.hash;if(!t)return;$(t+"-answer").collapse()}(),function(){if(0===$("#testimonials").length)return}(),$(".faq-dynamic").click((function(){$($(this).data("target")).collapse()})),$((function(){$('[data-toggle="tooltip"]').tooltip()})),$("[data-pricing]").click((function(r){var n=$(this).data("pricing");"toggle"===n?$(this).hasClass("pricing-monthly")?t():e():"monthly"===n?e():t()}))})),r(4684)})()})();
//# sourceMappingURL=front.js.map