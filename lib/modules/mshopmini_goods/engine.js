function getMiniPrice(price, koff){
       koff = parseFloat(koff);
       if (isNaN(koff)) koff = 1.00;
       return parseFloat(price.replace(/\D+/g,"")) * koff;
   }
