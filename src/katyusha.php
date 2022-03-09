<script>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/localforage.min.js' ?>	
</script>
<script>
	window.addEventListener('DOMContentLoaded', () => {
		const cssEksternal = document.querySelectorAll('link[rel=stylesheet]')
		if (cssEksternal.length > 0) {
			cssEksternal.forEach(css => {
				localforage.getItem(css.href).then(x => {
					if (x){
						css.outerHTML = `<style>${x}</style>`
					} else {
						fetch(css.href).then(x => x.text()).then(hasilnya => {
							localforage.setItem(css.href, hasilnya)
							css.outerHTML = `<style>${hasilnya}</style>`
						}).catch(x => console.log(x))
					}
				})
			})
		}

		// const semuaJs = document.querySelectorAll('script')
		// if (semuaJs.length > 0){
		// 	semuaJs.forEach(js => {
		// 		if (js.hasAttribute('src')){
		// 			localforage.getItem(js.src).then(adaDiStorage => {
		// 				if (adaDiStorage){
		// 					js.removeAttribute('src')
		// 					const semuaAttribute = [...js.attributes]
							
		// 					const elemenBaru = document.createElement('script')
		// 					for (let z of semuaAttribute){
		// 						elemenBaru.setAttribute(z.name, z.value)
		// 					}
		// 					elemenBaru.innerHTML = adaDiStorage
		// 					js.parentNode.replaceChild(elemenBaru, js)
		// 				} else {
		// 					fetch(js.src).then(ambil => ambil.text()).then(ambil => {
		// 						localforage.setItem(js.src, ambil)

		// 						js.removeAttribute('src')
		// 						const semuaAttribute = [...js.attributes]
								
		// 						const elemenBaru = document.createElement('script')
		// 						for (let z of semuaAttribute){
		// 							elemenBaru.setAttribute(z.name, z.value)
		// 						}
		// 						elemenBaru.innerHTML = ambil
		// 						js.parentNode.replaceChild(elemenBaru, js)
		// 					})
		// 				}
		// 			})
		// 		} else {
		// 			const semuaAttribute = [...js.attributes]
								
		// 			const elemenBaru = document.createElement('script')
		// 			for (let z of semuaAttribute){
		// 				elemenBaru.setAttribute(z.name, z.value)
		// 			}
		// 			elemenBaru.innerHTML = js.innerHTML
		// 			js.parentNode.replaceChild(elemenBaru, js)
		// 		}

		// 	})
		// }

		const gambar = document.querySelectorAll('img')
		if (gambar.length > 0){
			gambar.forEach(gambarnya => {
				localforage.getItem(gambarnya.src).then(x => {
					if (x){
						gambarnya.src = x
					} else {
						fetch(`https://scrappy-php.herokuapp.com/?url=${encodeURIComponent(gambarnya.src)}`).then(x => x.blob()).then(gambarFetch => {
							const reader = new FileReader
							reader.readAsDataURL(gambarFetch)
							reader.addEventListener('load', x => {
								const hasil = x.target.result
								localforage.setItem(gambarnya.src, hasil)
								gambarnya.src = hasil
							})
						}).catch(x => console.log(x))
					}
				})
			})
		}
	})
</script>