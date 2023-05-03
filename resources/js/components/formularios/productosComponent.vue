<template>
  <div>
    <form @submit.prevent="editarproducto(producto)" v-if="modoEditar">
      <h3>Editar producto</h3>
      <input type="text" class="form-control mb-2" 
        placeholder="Nombre de la producto" v-model="producto.nombre">
      
      <input type="text" class="form-control mb-2" 
        placeholder="Precio del producto" v-model="producto.precio">
      
      <input type="text" class="form-control mb-2" 
        placeholder="Stock del producto" v-model="producto.stock">
      
      {{ producto.categoria_id }}

    <select class="form-control mb-2" v-model="producto.categoria_id">
      <option disabled value="">Please select one</option>
        <option v-for="categoria in categorias"  v-bind:key="categoria.id" v-bind:value="categoria.id">
      {{ categoria.nombre }}
    </option>
    </select>
    

      <button class="btn btn-warning" type="submit">Editar</button>
      <button class="btn btn-danger" type="submit" 
        @click="cancelarEdicion">Cancelar</button>
    </form>


    <form @submit.prevent="agregar" v-else>
      <h3>Agregar producto</h3>
      <input type="text" class="form-control mb-2" 
        placeholder="Nombre de la producto" v-model="producto.nombre">
      <input type="text" class="form-control mb-2" 
        placeholder="Precio del producto" v-model="producto.precio">
        <input type="text" class="form-control mb-2" 
        placeholder="Stock del producto" v-model="producto.stock">

      {{ producto.categoria_id }}

    <select class="form-control mb-2" v-model="producto.categoria_id">
      <option disabled value="">Please select one</option>
        <option v-for="categoria in categorias"  v-bind:key="categoria.id" v-bind:value="categoria.id">
      {{ categoria.nombre }}
    </option>
    </select>

      <button class="btn btn-primary" type="submit">Agregar Producto</button>
    </form>


    <hr>

    <h3>Lista de productos:</h3>
    <ul class="list-group">
         <li class="list-group-item" v-for="(item, index) in productos" :key="index" >
          <span class="badge badge-primary float-right">{{item.updated_at}}</span>
          <p>{{item.nombre}}</p>
          <p>{{item.descripcion}}</p>
          <p>
            <button class="btn btn-warning btn-sm" 
                @click="editarFormulario(item)">Editar</button>
            <button class="btn btn-danger btn-sm" 
                @click="eliminarproducto(item, index)">Eliminar</button>
          </p>
        </li>
    </ul>


  </div>
</template>

<script>
export default {
  data() {
    return {
      productos: [],
      categorias: [],
      modoEditar: false,
      producto: {nombre: '', precio: '0.00',stock:'0',categoria_id:0}
    }
  },
  created(){
    axios.get('/productos').then(res=>{
      this.productos = res.data;
    });
    axios.get('/get_categorias').then(res=>{
      this.categorias = res.data;
    });
  },
  methods:{
    agregar(){
      if(this.producto.nombre.trim() === '' || this.producto.precio.trim() === ''){
        alert('Debes completar todos los campos antes de guardar');
        return;
      }
      const productoNueva = this.producto;
      this.producto = {nombre: '', descripcion: ''};    
      axios.post('/productos', productoNueva)
        .then((res) =>{
          const productoServidor = res.data;
          this.productos.push(productoServidor);
        })
    },
    editarFormulario(item){
      this.producto.nombre = item.nombre;
      this.producto.precio = item.precio;
      this.producto.stock = item.stock;
      this.producto.id = item.id;
      this.producto.categoria_id = item.categoria_id;
      this.modoEditar = true;
    },
    editarproducto(producto){
      const params = {nombre: producto.nombre, precio: producto.precio, stock: producto.stock, categoria_id: producto.categoria_id};
      axios.put(`/productos/${producto.id}`, params)
        .then(res=>{
          this.modoEditar = false;
          const index = this.productos.findIndex(item => item.id === producto.id);
          this.productos[index] = res.data;
        })
    },
    eliminarproducto(producto, index){
      const confirmacion = confirm(`Eliminar producto ${producto.nombre}`);
      if(confirmacion){
        axios.delete(`/productos/${producto.id}`)
          .then(()=>{
            this.productos.splice(index, 1);
          })
      }
    },
    cancelarEdicion(){
      this.modoEditar = false;
      this.producto = {nombre: '', descripcion: ''};
    },


            
  }
}
</script>