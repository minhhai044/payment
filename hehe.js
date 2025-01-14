const button = document.querySelector("#btnMomo");

const momo = async (amount) => {
  try {
    const url = "https://payment.test/api/index";

    const response = await axios.post(url, {
        amount,
    });

    window.location.href = response.data.url

    // console.log(response.data);
    

  } catch (error) {
    console.log(error);
  }
};

button.addEventListener("click", async () => {
  const amount = document.querySelector("#amount").value;

  await momo(amount);
});
