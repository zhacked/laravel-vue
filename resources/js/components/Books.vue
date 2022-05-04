<template>


    <div class="container">
        <v-row class="mt-5">
            <v-col class="col-md-12">
                <v-card-title>
                    <strong> Books </strong>
                    <v-spacer></v-spacer>
                    <v-card-actions class="card-tools">
                        <v-btn dark style="background-color:#66ff66 "
                            elevation="2"  @click="openModal">Add New Books</i></v-btn>
                    </v-card-actions>
                </v-card-title>


                    <v-simple-table class=" table-hover elevation-1">
                        <thead >
                            <tr class="text-center ">
                                <td>ID</td>
                                <td>Title</td>
                                <td>ISBN</td>
                                <td>Author</td>
                                <td>Publisher</td>
                                <td>Year Published</td>
                                <td>Category</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="books in books.data" :key="books.id" class="text-center">
                                <td>{{books.id}}</td>
                                <td>{{books.title}}</td>
                                <td>{{books.ISBN}}</td>
                                <td>{{books.Author}}</td>
                                <td>{{books.Publisher}}</td>
                                <td>{{books.YearPublished}}</td>
                                <td>{{books.Category}}</td>

                                <td>
                                    <button class="btn" style="background-color:#33ccff; color:white" @click="editModal(books)">
                                        <i class="fa fa-edit"></i> Update Book
                                    </button>

                                    <button class="btn btn-danger"  @click="deleteBook(books.id)">
                                    <i class="fa fa-trash"></i> Delete Book
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </v-simple-table>

                <v-card-title class="ma-0">
                    <pagination  :data="books" @pagination-change-page="getResults"></pagination>
                </v-card-title>
            </v-col>
        </v-row>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add Books</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Books</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateBooks() : createBooks()">
                    <div class="modal-body">
                        <div class="form-group">   <input v-model="booksForm.title" type="text" name="title"
                                placeholder="Book Title"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('title') }">
                            <has-error :form="booksForm" field="title"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="booksForm.ISBN" type="text" name="title"
                                placeholder="ISBN"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('ISBN') }">
                            <has-error :form="booksForm" field="ISBN"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="booksForm.Author" type="text" name="Author"
                                placeholder="Author Name"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('Author') }">
                            <has-error :form="booksForm" field="Author"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="booksForm.Publisher" type="text" name="Publisher"
                                placeholder="Publisher"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('Publisher') }">
                            <has-error :form="booksForm" field="Publisher"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="booksForm.YearPublished" type="text" name="YearPublished"
                                placeholder="Year Published"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('YearPublished') }">
                            <has-error :form="booksForm" field="YearPublished"></has-error>
                        </div>
                        <div class="form-group">
                            <input v-model="booksForm.Category" type="text" name="Category"
                                placeholder="Category"
                                class="form-control" :class="{ 'is-invalid': booksForm.errors.has('Category') }">
                            <has-error :form="booksForm" field="Category"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <v-btn type="button" dark color="error" elevation="2" data-dismiss="modal">Close</v-btn>
                        <v-btn v-show="editmode" dark type="submit" color="success" elevation="2">Update</v-btn>
                        <v-btn v-show="!editmode" dark type="submit" color="green"  elevation="2">Create</v-btn>
                    </div>

                </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                length: '',
                books:{},
                booksForm: new Form({
                    id: '',
                    title: '',
                    ISBN: '',
                    Author: '',
                    Publisher: '',
                    YearPublished: '',
                    Category: '',
                }),
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/books?page=' + page)
                    .then(response => {
                        this.books = response.data;
                    });
            },
            deleteBook(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                                this.booksForm.delete('api/books/'+id).then(()=>{
                                        swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                }).catch(()=> {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                         }
                    })
            },
            createBooks(){
                this.booksForm.post("api/books").then(({ data }) => {
                        swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )

                    Fire.$emit('AfterCreate');
                    $("#addNew").modal("hide");
                });
            },
            updateBooks(){
                this.booksForm.put('api/books/'+this.booksForm.id)
                .then(() => {
                     swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide');
                });
            },
            loadBooks(){
                axios.get("api/books").then(({ data }) => (this.books = data));
            },

            openModal() {
                this.editmode = false;
                $("#addNew").modal("show");
                this.booksForm.reset();
            },
            editModal(books){
                this.editmode = true;
                this.booksForm.reset();
                $('#addNew').modal('show');
                this.booksForm.fill(books);
            },



        },
        created() {

           this.loadBooks();
           Fire.$on('AfterCreate',() => {
               this.loadBooks();
           });
        }
    }
</script>

