# ban-hang



Mời thành viên nhóm
Đã tải lên ảnh
Đã tải lên ảnh
giả sử bạn là 1 thầy giáo bạn hãy viết cho tôi cách sử dụng ajax để làm chức năng mua hàng để không cần phải reload lại trang: tôi hiểu là khi bấm vào add-to-cart thì sẽ lấy id sản phẩm xong rồi gửi qua ajax và rồi ajax chuyển qua cho php để rồi hiện lên trang cart <!-- 
- click mua product mà k reload trang => dùng js(xu ly tren man hinh)

- qua trang cart.php ,lấy thông tin trong SS ra va hien thi ra table


click mua hang bằng js(Fe) <=  ajax(js, trao đổi data giữa frontend va backend)  => luu thông SS php(be) 

(ajax: chay ngầm)


thứ tự làm:
- click mua hàng thi lây ID product ra (dung js)
- co ID dùng ajax gui ID qua php (chay ngầm)
- qua PHP goi ID ra, viet sql lấy thông tin của product này theo ID (tra ve 1 mang)=> SS



$mangcon['qty'] = 1;

$_ss['CART'][] = $mangcon


[
	0:[
		price:..
		img:...
		title:...
		qty:2
		id:1
	],
	1:[
		price:..
		img:...
		title:...
		qty:2,
		id:1
	]
]

 -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<form id="userForm">
	  <input type="text" id="name" placeholder="Nhập tên" required>
	  <input type="email" id="email" placeholder="Nhập email" required>
	  <button type="submit">Gửi</button>
	</form>

	<div id="result"></div>

<script>
	
document.querySelector('#userForm').addEventListener('submit', async function(e) {
  e.preventDefault(); // Ngăn trang reload

  // Lấy dữ liệu từ form
  const name = document.querySelector('#name').value;
  const email = document.querySelector('#email').value;
  let id = 123

  // Gửi AJAX (fetch) (giong form)
  const response = await fetch('ajax_hanlde_cart.php', { //k có chi het
    method: 'POST',
    headers: {
      'Content-Type': 'application/json', // Gửi dạng JSON
    },
    body: JSON.stringify({ name, email, id })
  });

  const data = await response.json(); // Nhận phản hồi JSON từ PHP
  // alert("thanh cong")
  console.log(data)
  document.querySelector('#result').textContent = data.message;
});
</script>

</body>
</html>



Đã suy nghĩ trong 7s
Được, thầy sẽ giải thích theo đúng tư duy làm chức năng mua hàng bằng AJAX để không reload trang.

Hiện tại em đang hiểu khá đúng rồi, chỉ cần sắp lại cho rõ luồng và sửa vài chỗ trong code.

1. Tư duy đúng của chức năng add to cart bằng AJAX
Khi người dùng bấm Add to cart, ta không submit form kiểu cũ nữa, mà làm theo luồng này:

Bước 1: Frontend lấy id sản phẩm
Dùng JavaScript bắt sự kiện click vào nút mua hàng.

Bước 2: Gửi id qua PHP bằng AJAX
Dùng fetch() hoặc $.ajax() gửi dữ liệu ngầm lên server.

Bước 3: PHP nhận id
PHP sẽ:

lấy id từ request

query database lấy thông tin sản phẩm

lưu vào $_SESSION['CART']

Bước 4: PHP trả JSON về lại cho frontend
Ví dụ:

thêm thành công

tổng số lượng trong giỏ

tên sản phẩm

số lượng hiện tại

Bước 5: Frontend cập nhật giao diện ngay
Ví dụ:

hiện thông báo “Đã thêm vào giỏ hàng”

tăng số trên icon cart

render mini cart

=> Kết quả: không cần reload trang.

2. Em đang sai ở đâu trong đoạn code hiện tại
Trong JavaScript em có đoạn:

const data = await response.json();
Điều này có nghĩa là PHP phải trả về JSON hợp lệ.

Nhưng bên PHP ảnh chụp đang là:

echo $data['id'];
Nó chỉ trả về 123, không phải JSON đúng chuẩn.

Trong khi JS lại đang đòi:

response.json()
thì phải là dạng:

echo json_encode([
    'status' => true,
    'message' => 'Đã thêm vào giỏ hàng',
    'id' => $data['id']
]);
3. Cấu trúc chuẩn của add to cart bằng AJAX
Thầy chia thành 3 file cho dễ học:

product.php hoặc trang danh sách sản phẩm

ajax_handle_cart.php

cart.php

4. File frontend: trang sản phẩm
Ví dụ đơn giản:

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm</title>
</head>
<body>

<h2>Sản phẩm</h2>

<div class="product">
    <h3>Áo thun</h3>
    <p>Giá: 200000</p>
    <button class="add-to-cart" data-id="1">Add to cart</button>
</div>

<div class="product">
    <h3>Quần jean</h3>
    <p>Giá: 350000</p>
    <button class="add-to-cart" data-id="2">Add to cart</button>
</div>

<p>Giỏ hàng: <span id="cart-count">0</span> sản phẩm</p>
<div id="result"></div>

<script>
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', async function () {
        const productId = this.dataset.id;

        try {
            const response = await fetch('ajax_handle_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: productId
                })
            });

            const data = await response.json();

            if (data.status) {
                document.querySelector('#result').innerText = data.message;
                document.querySelector('#cart-count').innerText = data.cart_count;
            } else {
                document.querySelector('#result').innerText = data.message;
            }
        } catch (error) {
            console.error(error);
            document.querySelector('#result').innerText = 'Có lỗi xảy ra';
        }
    });
});
</script>

</body>
</html>
5. File PHP xử lý AJAX: ajax_handle_cart.php
Đây là file quan trọng nhất.

<?php
session_start();
header('Content-Type: application/json');

// Giả sử đây là dữ liệu database giả
$products = [
    1 => [
        'id' => 1,
        'title' => 'Áo thun',
        'price' => 200000,
        'img' => 'ao-thun.jpg'
    ],
    2 => [
        'id' => 2,
        'title' => 'Quần jean',
        'price' => 350000,
        'img' => 'quan-jean.jpg'
    ]
];

// Nhận JSON từ JS
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra id có gửi lên không
if (!isset($data['id'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Không nhận được ID sản phẩm'
    ]);
    exit;
}

$id = (int)$data['id'];

// Kiểm tra sản phẩm có tồn tại không
if (!isset($products[$id])) {
    echo json_encode([
        'status' => false,
        'message' => 'Sản phẩm không tồn tại'
    ]);
    exit;
}

$product = $products[$id];

// Nếu chưa có giỏ hàng thì tạo mới
if (!isset($_SESSION['CART'])) {
    $_SESSION['CART'] = [];
}

// Kiểm tra nếu sản phẩm đã có trong giỏ thì tăng qty
$found = false;

foreach ($_SESSION['CART'] as $key => $item) {
    if ($item['id'] == $id) {
        $_SESSION['CART'][$key]['qty'] += 1;
        $found = true;
        break;
    }
}

// Nếu chưa có thì thêm mới
if (!$found) {
    $product['qty'] = 1;
    $_SESSION['CART'][] = $product;
}

// Tính tổng số lượng sản phẩm trong cart
$cartCount = 0;
foreach ($_SESSION['CART'] as $item) {
    $cartCount += $item['qty'];
}

// Trả JSON về cho JS
echo json_encode([
    'status' => true,
    'message' => 'Đã thêm sản phẩm vào giỏ hàng',
    'cart_count' => $cartCount,
    'cart' => $_SESSION['CART']
]);
6. File cart.php để hiển thị giỏ hàng
Trang này vẫn là PHP bình thường. Nó đọc session và render ra table.

<?php
session_start();

$cart = $_SESSION['CART'] ?? [];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
</head>
<body>

<h2>Giỏ hàng</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Ảnh</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>

    <?php foreach ($cart as $item): ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo $item['title']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td>
                <img src="img/<?php echo $item['img']; ?>" width="50">
            </td>
            <td><?php echo $item['qty']; ?></td>
            <td><?php echo $item['price'] * $item['qty']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
7. Cấu trúc dữ liệu giỏ hàng trong session
Đúng như em ghi chú, session sẽ có dạng:

$_SESSION['CART'] = [
    [
        'id' => 1,
        'title' => 'Áo thun',
        'price' => 200000,
        'img' => 'ao-thun.jpg',
        'qty' => 2
    ],
    [
        'id' => 2,
        'title' => 'Quần jean',
        'price' => 350000,
        'img' => 'quan-jean.jpg',
        'qty' => 1
    ]
];
Đây là cấu trúc rất phổ biến.

8. Thầy diễn giải lại theo kiểu “dễ nhớ”
Em có thể nhớ bằng câu này:

JS lo phần bấm nút và gửi id.
PHP lo phần nhận id, tìm sản phẩm, lưu session.
Cart.php lo phần đọc session và hiển thị.

9. Sửa code em đang có theo đúng hướng
Code em đang viết là form nhập name, email, id. Nó đang giống demo AJAX hơn là demo cart.

Nếu muốn sửa ít nhất có thể, em làm như sau.

HTML
<form id="userForm">
  <input type="text" id="name" placeholder="Nhập tên" required>
  <input type="email" id="email" placeholder="Nhập email" required>
  <button type="submit">Gửi</button>
</form>

<div id="result"></div>
JS
<script>
document.querySelector('#userForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.querySelector('#name').value;
    const email = document.querySelector('#email').value;
    const id = 123;

    const response = await fetch('ajax_handle_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name, email, id })
    });

    const data = await response.json();
    console.log(data);
    document.querySelector('#result').textContent = data.message;
});
</script>
PHP
<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

echo json_encode([
    'status' => true,
    'message' => 'Đã nhận được dữ liệu',
    'id' => $data['id'],
    'name' => $data['name'],
    'email' => $data['email']
]);
Điểm mấu chốt là:

JS dùng response.json()

PHP phải echo json_encode(...)

10. Phiên bản thực tế cho mua hàng
Trong dự án bán hàng thật, khi click mua hàng em thường làm thế này:

HTML nút mua
<button class="add-to-cart" data-id="5">Mua hàng</button>
JS
document.addEventListener('click', async function(e) {
    if (e.target.classList.contains('add-to-cart')) {
        const id = e.target.dataset.id;

        const response = await fetch('ajax_handle_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        });

        const data = await response.json();

        if (data.status) {
            alert(data.message);
        }
    }
});
11. Những lỗi hay gặp
Lỗi 1: Sai tên file
Trong ảnh em đang dùng:

fetch('ajax_hanlde_cart.php')
Nhưng tên đúng hình như phải là:

fetch('ajax_handle_cart.php')
Em bị đảo chữ handle thành hanlde.

Lỗi 2: PHP không trả JSON mà JS lại parse JSON
Sai kiểu:

echo $data['id'];
Đúng kiểu:

echo json_encode(['id' => $data['id']]);
Lỗi 3: Quên session_start()
Muốn lưu giỏ hàng bằng session thì đầu file PHP phải có:

session_start();
Lỗi 4: Add trùng sản phẩm nhưng không tăng số lượng
Nếu sản phẩm đã có trong giỏ thì phải tăng qty, không nên push thêm bản ghi trùng y hệt.

12. Quy trình chuẩn của chức năng cart
Em có thể học thuộc sơ đồ này:

Click Add to cart
-> JS lấy product_id
-> fetch/ajax gửi product_id qua PHP
-> PHP nhận id
-> PHP query DB lấy sản phẩm
-> PHP lưu vào $_SESSION['CART']
-> PHP trả JSON về
-> JS cập nhật giao diện
-> Khi vào cart.php thì đọc session ra hiển thị
13. Kết luận như một thầy giáo chốt bài
Em đang hiểu đúng nền tảng rồi. Chỉ cần nhớ 3 ý:

AJAX không tự chuyển trang, nó chỉ gửi dữ liệu ngầm giữa frontend và backend.

PHP không tự hiện lên cart, mà nhiệm vụ của PHP là lưu dữ liệu vào session hoặc database.

Trang cart.php khi mở ra sẽ đọc $_SESSION['CART'] để hiển thị.

Nói ngắn gọn:

Bấm mua → JS gửi id

PHP nhận id → lấy sản phẩm → lưu session

JS nhận phản hồi → cập nhật giao diện ngay

cart.php → lấy session ra in table

Nếu em muốn, ở tin nhắn tiếp theo thầy sẽ viết cho em một bộ code hoàn chỉnh mini project add to cart bằng AJAX + cart.php + tăng giảm số lượng + xoá sản phẩm theo kiểu dễ học nhất.





