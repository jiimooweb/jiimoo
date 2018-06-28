<template>
    <div :class="'tip '+slass">
        <p>{{value}}</p>
    </div>
</template>
<script>
export default {
    name: 'tip',
    props:{
        value:'',
        slass:''
        // warning success primary default danger\
    }
}
</script>
<style>
    .tip{
        padding: 8px 16px;
        border-radius: 4px;
        margin: 20px 0;
    }
    .tip>p{
            font-size: 14px;
            color: #5e6d82;
            line-height: 1.5em;
    }
    .tip.default{
        border-left: 5px solid #505050;
        background-color: #ececec;
    }
    .tip.primary{
        border-left: 5px solid #50bfff;
        background-color: #ecf8ff;
    }
    .tip.success{
        border-left: 5px solid #47df2c;
        background-color: #e8fff1;
    }
    .tip.warning{
        border-left: 5px solid #ffda5e;
        background-color: #fffbd4;
    }
    .tip.danger{
        border-left: 5px solid #fe6c6f;
        background-color: #fff6f7;
    }
</style>

