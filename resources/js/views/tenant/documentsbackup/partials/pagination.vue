<template>
    <nav aria-label="pagination">
        <ul class="pagination">
            <li class="page-item" :class="pagination.current_page <= 1 ? 'disabled' : ''"><a class="page-link" @click.prevent="changePage(1)">Primero</a></li>
            <li class="page-item" :class="pagination.current_page <= 1 ? 'disabled' : ''"><a class="page-link" @click.prevent="changePage(pagination.current_page - 1)">Anterior</a></li>
            <li v-for="page in pages" class="page-item" :class="isCurrentPage(page) ? 'active' : ''">
                <a class="page-link" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li class="page-item" :class="pagination.current_page >= pagination.last_page ? 'disabled' : ''"><a class="page-link" @click.prevent="changePage(pagination.current_page + 1)">Siguiente</a></li>
            <li class="page-item" :class="pagination.current_page >= pagination.last_page ? 'disabled' : ''"><a class="page-link" @click.prevent="changePage(pagination.last_page)">Ultimo</a></li>
        </ul>
    </nav>
</template>

<style>
    .pagination {
        margin-top: 40px;
    }
</style>

<script>
    export default {
        props: ['pagination'],
        methods: {
            isCurrentPage(page) {
                return this.pagination.current_page === page;
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = [];
                let i;
                for (i = 1; i <= this.pagination.last_page; i++) { 
                    pages.push(i);
                }
                return pages;
            }
        }
    }
</script>