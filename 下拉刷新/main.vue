<template>
  <div style="position: absolute;left:0;top:0;width:100%;">

      <Header1></Header1>
      <Slider></Slider>
      <pullDown :callback="fn" :autoLoad="true">
           <List :data="datas" :index="index"></List>
      </pullDown>
  </div>
</template>

<script>
    import Header1 from "@/components/header"
    import Slider from "@/components/slider"
    import List from "@/components/list"
    import pullDown from "@/components/pullDown"
    export default {
        name: 'hello',
        components:{Header1,Slider,List,pullDown},
        data () {
            return {
                datas:[],
                page:0,
                index:["title"]
            }
        },
        methods:{
           fn(obj){
               /*用户体验*/
               if(this.$root.routerPlace){
                   this.datas=this.$root.datas;
                   this.$root.page= this.$root.page?++this.$root.page:this.page;
                   this.page=this.$root.page;
                   return;
               }
               fetch("/ajax/news/select?page="+this.page).then(function(e){
                   return e.json();
               }).then((e)=>{
                   setTimeout(()=>{
                       this.datas=e.concat(this.datas);
                       this.$root.datas=this.datas;
                       obj.over();
                       this.page++;
                       this.$root.page=this.page;
                   },2000)

               })
           }

        }







    }
</script>

<style scoped>

</style>