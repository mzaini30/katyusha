<script>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/localforage.min.js' ?>	
</script>
<script>
	let versi = 'base'
	if (typeof versiKatyusha != 'undefined') {
		versi = versiKatyusha
	}

	let ringkasan = {
		versi: '',
		link: []
	}
	ringkasan.versi = versi

	window.addEventListener('DOMContentLoaded', () => {
		const cssEksternal = document.querySelectorAll('link[rel=stylesheet]')
		if (cssEksternal.length > 0) {
			cssEksternal.forEach(css => {
				localforage.getItem(`${css.href}-${versi}`).then(x => {
					if (x){
						css.outerHTML = `<style>${x}</style>`
					} else {
						fetch(css.href).then(x => x.text()).then(hasilnya => {
							localforage.setItem(`${css.href}-${versi}`, hasilnya)
							css.outerHTML = `<style>${hasilnya}</style>`
						}).catch(x => console.log(x))
					}
				})
			})
		}

		const gambar = document.querySelectorAll('img')
		if (gambar.length > 0){
			gambar.forEach(gambarnya => {
				localforage.getItem(`${gambarnya.src}-${versi}`).then(x => {
					if (x){
						gambarnya.src = x
					} else {
						fetch(`https://scrappy-php.herokuapp.com/?url=${encodeURIComponent(gambarnya.src)}`).then(x => x.blob()).then(gambarFetch => {
							const reader = new FileReader
							reader.readAsDataURL(gambarFetch)
							reader.addEventListener('load', x => {
								const hasil = x.target.result
								localforage.setItem(`${gambarnya.src}-${versi}`, hasil)
								gambarnya.src = hasil
							})
						}).catch(x => console.log(x))
					}
				})
			})
		}

		const semuaJs = document.querySelectorAll('script')
		if (semuaJs.length > 0){
			semuaJs.forEach(js => {
				if (js.hasAttribute('src')){
					ringkasan.link = [...ringkasan.link, `${js.src}-${versi}`]
					
					if (localStorage.getItem(`${js.src}-${versi}`)){
						const isinya = localStorage.getItem(`${js.src}-${versi}`)
						js.removeAttribute('src')
						const semuaAttribute = [...js.attributes]
						
						const elemenBaru = document.createElement('script')
						for (let z of semuaAttribute){
							elemenBaru.setAttribute(z.name, z.value)
						}
						elemenBaru.innerHTML = isinya
						js.parentNode.replaceChild(elemenBaru, js)
					} else {
						fetch(js.src).then(ambil => ambil.text()).then(ambil => {
							localStorage.setItem(`${js.src}-${versi}`, ambil)

							js.removeAttribute('src')
							const semuaAttribute = [...js.attributes]
							
							const elemenBaru = document.createElement('script')
							for (let z of semuaAttribute){
								elemenBaru.setAttribute(z.name, z.value)
							}
							elemenBaru.innerHTML = ambil
							js.parentNode.replaceChild(elemenBaru, js)
						})
					}
				} else {
					const semuaAttribute = [...js.attributes]
								
					const elemenBaru = document.createElement('script')
					for (let z of semuaAttribute){
						elemenBaru.setAttribute(z.name, z.value)
					}
					elemenBaru.innerHTML = js.innerHTML
					js.parentNode.replaceChild(elemenBaru, js)
				}

			})

			// bersih-bersih localStorage
			if (localStorage.scriptKatyusha){
				let scriptKatyusha = JSON.parse(localStorage.scriptKatyusha)
				if (scriptKatyusha.versi != ringkasan.versi){
					for (let x of scriptKatyusha.link){
						localStorage.removeItem(x)
					}
				}
			}
			localStorage.scriptKatyusha = JSON.stringify(ringkasan)
		}
	})
</script>