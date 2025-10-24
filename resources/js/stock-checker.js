// resources/js/stock-checker.js
document.addEventListener('alpine:init', () => {
    Alpine.data('stockChecker', (inventarisDataJson) => ({
        selectedInventarisId: '',
        inventarisData: inventarisDataJson,
        currentStock: 0,
        itemType: '',
        stockUrlTemplate: '/api/inventaris/{inventaris}/stock', // Simpan template URL

        init() {
             // Inisialisasi selectedInventarisId jika ada old value dari server
             const initialId = document.getElementById('item_id')?.value; // Ambil dari select
             if (initialId) {
                 this.selectedInventarisId = initialId;
                 this.updateStockInfo();
             }
             // Atau bisa inject old('item_id') dari blade ke sini
        },

        updateStockInfo() {
            const selectedItem = this.inventarisData.find(item => item.id == this.selectedInventarisId);
            if (selectedItem) {
                this.itemType = selectedItem.kategori;
                if (this.itemType === 'habis_pakai') {
                    this.currentStock = 'Loading...'; // Tampilkan loading
                    const url = this.stockUrlTemplate.replace('{inventaris}', selectedItem.id);
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            this.currentStock = data.sisa_stok !== undefined ? data.sisa_stok : 'Error';
                        })
                        .catch(error => {
                            console.error('Error fetching stock:', error);
                            this.currentStock = 'Error';
                        });
                } else {
                    // Logika untuk non-habis pakai (ambil dari data item langsung)
                    this.currentStock = selectedItem.kondisi_baik; // Misalnya kondisi baik
                    // Atau: this.currentStock = 'Tidak Berlaku';
                }
            } else {
                this.currentStock = 0;
                this.itemType = '';
            }
        }
    }));
});
