<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\Product;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\progress;

class FeedProductReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:feed-product-reviews {--min=2} {--max=15} {--min-rating=4} {--max-rating=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add some initial product reviews to look good';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::select('id')->get();

        if ($this->option('max') < $this->option('min')) {
            error('Max should be greater than min');
            return;
        }

        if ($this->option('min') < 0) {
            error('Min should be greater than 0');
            return;
        }

        if($this->option('max-rating') < $this->option('min-rating')) {
            error('Max rating should be greater than min rating');
            return;
        }

        if ($this->option('min-rating') <= 0) {
            error('Min rating should be greater than 0');
            return;
        }

        $reviews = collect($this->reviews());

        progress(
            label: 'Feeding product reviews',
            steps: $products,
            callback: function ($product) use ($reviews) {
                $min = $this->option('min');
                $max = $this->option('max');

                $reviews_count = rand($min, $max);

                foreach ($reviews->take($reviews_count) as $review) {
                    $product->reviews()->create([
                        'name' => $review['name'],
                        'email' => $review['email'],
                        'rating' => rand($this->option('min-rating'), $this->option('max-rating')),
                        'title' => $review['title'],
                        'content' => $review['content'],
                    ]);
                }
            }
        );
    }

    public function reviews()
    {
        return [
            [
                'name' => 'Aditya Choudhary',
                'email' => 'aditya.choudhary88@gmail.com',
                'title' => 'Fantastic Product!',
                'content' => 'I’m really impressed with this product. It’s well-designed, easy to use, and feels very durable. The delivery was prompt, and the packaging was secure, ensuring no damage. I’ve been using it for a week, and it performs flawlessly. The only thing is the manual could be a bit more detailed, but overall, a great purchase!'
            ],
            [
                'name' => 'Shreya Iyer',
                'email' => 'shreya.iyer25@yahoo.co.in',
                'title' => 'Ekdam Badhiya Hai',
                'content' => 'Yeh product bilkul paisa vasool hai! Quality bahut achhi hai, and use karne mein bhi asaan hai. Delivery time pe hui, aur packaging bhi solid thi. Maine isse daily use kiya, aur koi issue nahi aaya. Bas thodi si price kam hoti toh aur maza aata. Highly recommend karungi!'
            ],
            [
                'name' => 'Vikrant Rathore',
                'email' => 'vikrant.rathore72@outlook.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is absolutely worth the price. It’s sturdy, performs as promised, and looks great too. The setup was straightforward, and I was using it within minutes. Delivery took a couple of days, which was fine, but faster shipping would be a bonus. I’m very satisfied and would buy from this brand again.'
            ],
            [
                'name' => 'Nisha Bhandari',
                'email' => 'nisha.bhandari19@gmail.com',
                'title' => 'Really Happy!',
                'content' => 'I’m thrilled with this purchase! The product is high-quality and super convenient for daily use. It arrived in perfect condition, thanks to the excellent packaging. The customer support team was quick to answer my query about setup, which I appreciated. My only suggestion would be to include a more detailed guide. Definitely recommend it!'
            ],
            [
                'name' => 'Siddhant Jha',
                'email' => 'siddhant.jha63@yahoo.co.in',
                'title' => 'Great Buy',
                'content' => 'This product has exceeded my expectations. It’s easy to use, well-made, and performs reliably every time. The delivery was on time, and the packaging ensured it arrived without a scratch. I’ve recommended it to my friends already. The price is reasonable, but a slight discount would make it even more appealing.'
            ],
            [
                'name' => 'Tanvi Sood',
                'email' => 'tanvi.sood47@gmail.com',
                'title' => 'Mast Product Hai',
                'content' => 'Is product ne dil jeet liya! Bahut hi user-friendly hai aur quality top-notch hai. Delivery jaldi hui, aur packaging bhi badhiya thi. Maine isse regularly use kiya, aur ab tak koi problem nahi. Thodi si instructions clearer ho sakti thi, lekin overall ekdum zabardast hai!'
            ],
            [
                'name' => 'Raghav Malhotra',
                'email' => 'raghav.malhotra94@outlook.com',
                'title' => 'Super Satisfied',
                'content' => 'I’m extremely happy with this product. It’s durable, easy to operate, and looks exactly as shown in the pictures. The delivery was quick, and the packaging was very secure. I’ve been using it for various tasks, and it’s been perfect. A more comprehensive manual would help, but it’s still a fantastic buy.'
            ],
            [
                'name' => 'Ishita Dube',
                'email' => 'ishita.dube31@gmail.com',
                'title' => 'Value for Money',
                'content' => 'This product is a great value for the price. It’s well-crafted, performs smoothly, and has made my daily routine easier. The delivery was on schedule, and the packaging was top-notch. I contacted customer service with a small query, and they were very helpful. My only wish is for faster shipping options in the future.'
            ],
            [
                'name' => 'Karan Vaidya',
                'email' => 'karan.vaidya58@yahoo.co.in',
                'title' => 'Highly Recommend',
                'content' => 'I can’t recommend this product enough! It’s reliable, easy to use, and feels like a premium item. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The instructions could be a bit clearer, but that’s a minor issue. Great purchase!'
            ],
            [
                'name' => 'Aarushi Tyagi',
                'email' => 'aarushi.tyagi76@gmail.com',
                'title' => 'Awesome Quality',
                'content' => 'The quality of this product is outstanding. It’s sturdy, works perfectly, and is very user-friendly. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a while, and it’s been a game-changer. A slightly lower price would make it even more attractive, but I’m very happy.'
            ],
            [
                'name' => 'Pranav Kulkarni',
                'email' => 'pranav.kulkarni22@outlook.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product ekdum first-class hai! Quality aur performance dono lajawab hai. Delivery time pe hui, aur packaging bhi solid thi. Maine isse har roz use kiya, aur bilkul satisfied hoon. Bas thodi si manual mein aur details hoti toh perfect tha. Sabko recommend karunga!'
            ],
            [
                'name' => 'Rhea Saxena',
                'email' => 'rhea.saxena45@gmail.com',
                'title' => 'Loved It!',
                'content' => 'I absolutely love this product! It’s well-designed, super convenient, and performs exactly as promised. The delivery was on time, and the packaging was very secure. I’ve been using it regularly, and it’s made my life so much easier. The only thing I’d suggest is a more detailed setup guide. Highly satisfied!'
            ],
            [
                'name' => 'Devansh Grover',
                'email' => 'devansh.grover89@yahoo.co.in',
                'title' => 'Great Experience',
                'content' => 'Buying this product was a great decision. It’s durable, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured no damage during transit. I’ve been using it for various purposes, and it’s been fantastic. A slightly faster delivery option would be nice, but I’m very pleased overall.'
            ],
            [
                'name' => 'Simran Kaur',
                'email' => 'simran.kaur33@outlook.com',
                'title' => 'Totally Worth It',
                'content' => 'This product is worth every penny. It’s high-quality, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, ensuring the product arrived safely. I’ve been using it daily, and it’s been perfect. The manual could use more details, but that’s a small issue. Great buy!'
            ],
            [
                'name' => 'Yashwant Rana',
                'email' => 'yashwant.rana67@gmail.com',
                'title' => 'Ek Number Product',
                'content' => 'Is product ne sach mein dil khush kar diya! Quality ekdum badhiya hai, aur use karna bhi bahut asaan hai. Delivery time pe hui, aur packaging ne product ko safe rakha. Maine isse regularly use kiya, aur koi shikayat nahi. Thodi si price kam hoti toh aur achha tha. Zabardast hai!'
            ],
            [
                'name' => 'Mansi Aggarwal',
                'email' => 'mansi.aggarwal14@yahoo.co.in',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-made, easy to use, and performs flawlessly. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it for a week, and it’s been a great addition to my routine. A more detailed instruction manual would be helpful, but I’m really happy.'
            ],
            [
                'name' => 'Nikhil Shinde',
                'email' => 'nikhil.shinde81@gmail.com',
                'title' => 'Amazing Buy',
                'content' => 'This product is simply amazing. It’s durable, user-friendly, and looks exactly as advertised. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been very reliable. The only thing I’d change is to include a clearer setup guide. Highly recommend this!'
            ],
            [
                'name' => 'Ananya Pathak',
                'email' => 'ananya.pathak38@outlook.com',
                'title' => 'Super Product',
                'content' => 'This product is fantastic! It’s well-crafted, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for various tasks, and it’s been flawless. A slightly faster shipping option would be great, but I’m extremely satisfied with the purchase.'
            ],
            [
                'name' => 'Rishabh Tomar',
                'email' => 'rishabh.tomar56@yahoo.co.in',
                'title' => 'Paisa Vasool',
                'content' => 'Yeh product bilkul paisa vasool hai! Quality top-notch hai, aur use karne mein koi dikkat nahi. Delivery jaldi hui, aur packaging bhi achhi thi. Maine isse har roz use kiya, aur performance ekdum badhiya hai. Bas manual thoda aur detailed hota toh perfect tha. Sabko bolunga isse try karo!'
            ],
            [
                'name' => 'Sanya Mehrotra',
                'email' => 'sanya.mehrotra92@gmail.com',
                'title' => 'Really Nice',
                'content' => 'I’m really happy with this product. It’s sturdy, easy to use, and performs exactly as expected. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. The instructions could be a bit clearer, but overall, a solid purchase.'
            ],
            [
                'name' => 'Arnav Bhargava',
                'email' => 'arnav.bhargava29@outlook.com',
                'title' => 'Great Quality',
                'content' => 'The quality of this product is top-notch. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been perfect. A more detailed manual would be a nice addition, but I’m very satisfied with this buy.'
            ],
            [
                'name' => 'Kritika Sen',
                'email' => 'kritika.sen74@gmail.com',
                'title' => 'Loved the Product',
                'content' => 'I absolutely love this product! It’s user-friendly, durable, and performs flawlessly. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for a week, and it’s made my tasks so much easier. The only suggestion is to include a clearer instruction guide. Highly recommend it!'
            ],
            [
                'name' => 'Saurabh Wadhwa',
                'email' => 'saurabh.wadhwa41@yahoo.co.in',
                'title' => 'Very Happy',
                'content' => 'This product has made me very happy. It’s well-made, easy to use, and looks great too. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it regularly, and it’s been very reliable. A slightly faster delivery option would be nice, but I’m thrilled with the purchase.'
            ],
            [
                'name' => 'Vidya Nambiar',
                'email' => 'vidya.nambiar68@outlook.com',
                'title' => 'Ekdam Zabardast',
                'content' => 'Yeh product ekdum zabardast hai! Quality aur design dono lajawab hai. Delivery time pe hui, aur packaging ne product ko safe rakha. Maine isse daily use kiya, aur performance bilkul perfect hai. Thodi si instructions aur clear hoti toh aur achha tha. Sabko recommend karungi!'
            ],
            [
                'name' => 'Tushar Goyal',
                'email' => 'tushar.goyal15@gmail.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product is a fantastic buy. It’s durable, easy to operate, and performs as promised. The delivery was quick, and the packaging was excellent, ensuring no damage. I’ve been using it for various tasks, and it’s been flawless. The manual could use more details, but that’s a minor issue. I’m very satisfied!'
            ],
            [
                'name' => 'Pallavi Nair',
                'email' => 'pallavi.nair83@yahoo.co.in',
                'title' => 'Worth It',
                'content' => 'This product is completely worth the price. It’s well-crafted, user-friendly, and looks exactly as shown. The delivery was on time, and the packaging was secure, keeping the product safe. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be helpful, but I’m very happy.'
            ],
            [
                'name' => 'Akhil Mehra',
                'email' => 'akhil.mehra50@gmail.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s sturdy, performs reliably, and is very easy to use. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been fantastic. The only thing I’d suggest is a more detailed manual. Highly recommend this product!'
            ],
            [
                'name' => 'Riya Khanna',
                'email' => 'riya.khanna27@outlook.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, easy to set up, and performs flawlessly. The delivery was quick, and the packaging was excellent, ensuring no damage. I’ve been using it for a week, and it’s been very reliable. A slightly faster shipping option would be nice, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Vishal Bhardwaj',
                'email' => 'vishal.bhardwaj64@yahoo.co.in',
                'title' => 'Mast Hai',
                'content' => 'Yeh product bilkul mast hai! Quality ekdum badhiya hai, aur use karne mein maza aata hai. Delivery jaldi hui, aur packaging bhi solid thi. Maine isse har roz use kiya, aur koi problem nahi aayi. Bas thodi si price kam hoti toh aur achha tha. Definitely recommend karunga!'
            ],
            [
                'name' => 'Snehal Deshmukh',
                'email' => 'snehal.deshmukh91@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s durable, easy to use, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. The instructions could be clearer, but overall, a fantastic buy.'
            ],
            [
                'name' => 'Rohan Sethi',
                'email' => 'rohan.sethi38@outlook.com',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s well-made, user-friendly, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been flawless. A more detailed manual would be helpful, but that’s a minor issue. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Ankita Vyas',
                'email' => 'ankita.vyas75@gmail.com',
                'title' => 'Great Value',
                'content' => 'This product offers great value for money. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for various tasks, and it’s been perfect. The only suggestion is to include a clearer setup guide. Highly recommend!'
            ],
            [
                'name' => 'Sachin Raut',
                'email' => 'sachin.raut22@yahoo.co.in',
                'title' => 'Ekdam Perfect',
                'content' => 'Yeh product ekdam perfect hai! Quality aur design dono top-class hai. Delivery time pe hui, aur packaging ne product ko safe rakha. Maine isse daily use kiya, aur performance bilkul lajawab hai. Thodi si manual mein aur details hoti toh aur achha tha. Sabko bolunga isse buy karo!'
            ],
            [
                'name' => 'Rohan Banerjee',
                'email' => 'rohan.banerjee77@gmail.com',
                'title' => 'Excellent Product!',
                'content' => 'This product is truly impressive. It’s well-designed, easy to use, and feels very sturdy. The delivery was prompt, and the packaging ensured it arrived without any damage. I’ve been using it daily, and it performs flawlessly. The manual could be a bit more detailed, but overall, it’s a fantastic purchase I’d recommend to anyone.'
            ],
            [
                'name' => 'Anika Shetty',
                'email' => 'anika.shetty19@yahoo.co.in',
                'title' => 'Great Value',
                'content' => 'I’m really happy with this product. It’s high-quality, user-friendly, and worth every penny. The delivery was on time, and the packaging was secure, keeping the product in perfect condition. I’ve been using it for a week, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very satisfied overall.'
            ],
            [
                'name' => 'Saurav Mathur',
                'email' => 'saurav.mathur62@outlook.com',
                'title' => 'Highly Recommend',
                'content' => 'This product is outstanding. It’s durable, performs as promised, and is very easy to set up. The delivery was quick, and the packaging was excellent, ensuring no damage during transit. I’ve recommended it to my friends already. The only suggestion is to include a more comprehensive manual. Great buy overall!'
            ],
            [
                'name' => 'Priyanka Seth',
                'email' => 'priyanka.seth44@gmail.com',
                'title' => 'Really Satisfied',
                'content' => 'I’m thrilled with this purchase. The product is well-crafted, easy to operate, and looks exactly as advertised. The delivery was on schedule, and the packaging was sturdy, protecting the item perfectly. I’ve been using it daily, and it’s made my tasks much easier. A faster shipping option would be nice, but I’m very happy.'
            ],
            [
                'name' => 'Vikas Nanda',
                'email' => 'vikas.nanda88@yahoo.co.in',
                'title' => 'Amazing Quality',
                'content' => 'The quality of this product is top-notch. It’s sturdy, reliable, and very user-friendly. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for various purposes, and it’s been flawless. The instructions could be a bit clearer, but that’s a minor issue. Highly recommend this product!'
            ],
            [
                'name' => 'Shivangi Rao',
                'email' => 'shivangi.rao31@outlook.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product is a fantastic buy. It’s well-designed, performs reliably, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Nitin Khurana',
                'email' => 'nitin.khurana55@gmail.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s durable, easy to use, and performs as expected. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it daily, and it’s been very reliable. The only thing I’d suggest is a clearer setup guide. Great purchase overall!'
            ],
            [
                'name' => 'Riya Goswami',
                'email' => 'riya.goswami28@yahoo.co.in',
                'title' => 'Superb Product',
                'content' => 'This product is superb. It’s well-crafted, user-friendly, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for a week, and it’s been flawless. The manual could use more details, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Arjun Puri',
                'email' => 'arjun.puri66@outlook.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s sturdy, easy to operate, and performs reliably. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. A more comprehensive manual would be nice, but I’m really satisfied with the purchase.'
            ],
            [
                'name' => 'Komal Vohra',
                'email' => 'komal.vohra93@gmail.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s well-designed, reliable, and very easy to use. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. The instructions could be clearer, but overall, a solid purchase.'
            ],
            [
                'name' => 'Akhilesh Sharma',
                'email' => 'akhilesh.sharma39@yahoo.co.in',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, user-friendly, and performs as promised. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for various tasks, and it’s been flawless. The only suggestion is to include a more detailed manual. Highly recommend this product!'
            ],
            [
                'name' => 'Tanya Luthra',
                'email' => 'tanya.luthra72@gmail.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-crafted, easy to set up, and performs reliably. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been very dependable. A clearer instruction guide would be helpful, but I’m very happy with this purchase.'
            ],
            [
                'name' => 'Siddhesh Patil',
                'email' => 'siddhesh.patil16@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Neelam Arora',
                'email' => 'neelam.arora84@yahoo.co.in',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-designed, performs reliably, and is very easy to use. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed setup guide. Great purchase!'
            ],
            [
                'name' => 'Rishabh Chawla',
                'email' => 'rishabh.chawla51@gmail.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Shruti Bhasin',
                'email' => 'shruti.bhasin27@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Vivek Tandon',
                'email' => 'vivek.tandon63@yahoo.co.in',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Poonam Grover',
                'email' => 'poonam.grover95@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Kunal Mehra',
                'email' => 'kunal.mehra42@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Swati Khera',
                'email' => 'swati.khera78@yahoo.co.in',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Ankur Jindal',
                'email' => 'ankur.jindal34@gmail.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Megha Saini',
                'email' => 'megha.saini69@outlook.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Sachin Bisht',
                'email' => 'sachin.bisht15@yahoo.co.in',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Richa Malhotra',
                'email' => 'richa.malhotra82@gmail.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Ravi Ahuja',
                'email' => 'ravi.ahuja58@outlook.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Nidhi Chhabra',
                'email' => 'nidhi.chhabra26@yahoo.co.in',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Prateek Grover',
                'email' => 'prateek.grover71@gmail.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Sonali Vohra',
                'email' => 'sonali.vohra43@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Mukesh Saini',
                'email' => 'mukesh.saini97@yahoo.co.in',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Anjali Bisht',
                'email' => 'anjali.bisht64@gmail.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Vikram Chawla',
                'email' => 'vikram.chawla29@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Shalini Malhotra',
                'email' => 'shalini.malhotra85@yahoo.co.in',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Rohit Ahuja',
                'email' => 'rohit.ahuja52@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Pallavi Chhabra',
                'email' => 'pallavi.chhabra18@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Sanjay Grover',
                'email' => 'sanjay.grover76@yahoo.co.in',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Kavita Saini',
                'email' => 'kavita.saini41@gmail.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Deepak Bisht',
                'email' => 'deepak.bisht93@outlook.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Kriti Chawla',
                'email' => 'kriti.chawla67@yahoo.co.in',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Manish Malhotra',
                'email' => 'manish.malhotra24@gmail.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Pooja Ahuja',
                'email' => 'pooja.ahuja89@outlook.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Rahul Chhabra',
                'email' => 'rahul.chhabra56@yahoo.co.in',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Shilpa Grover',
                'email' => 'shilpa.grover32@gmail.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Anil Saini',
                'email' => 'anil.saini78@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Reena Bisht',
                'email' => 'reena.bisht45@yahoo.co.in',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, andHannah. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Vijay Chawla',
                'email' => 'vijay.chawla91@gmail.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Seema Malhotra',
                'email' => 'seema.malhotra67@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Amit Ahuja',
                'email' => 'amit.ahuja23@yahoo.co.in',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Neha Chhabra',
                'email' => 'neha.chhabra89@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Suresh Grover',
                'email' => 'suresh.grover56@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Ritu Saini',
                'email' => 'ritu.saini32@yahoo.co.in',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht78@outlook.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Anita Chawla',
                'email' => 'anita.chawla45@gmail.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Rakesh Malhotra',
                'email' => 'rakesh.malhotra91@yahoo.co.in',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Sneha Ahuja',
                'email' => 'sneha.ahuja67@outlook.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Vivek Chhabra',
                'email' => 'vivek.chhabra23@gmail.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover89@yahoo.co.in',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Sanjay Saini',
                'email' => 'sanjay.saini56@outlook.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Kavita Bisht',
                'email' => 'kavita.bisht32@gmail.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Deepak Chawla',
                'email' => 'deepak.chawla78@outlook.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Kriti Malhotra',
                'email' => 'kriti.malhotra45@yahoo.co.in',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Manish Ahuja',
                'email' => 'manish.ahuja91@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Pooja Chhabra',
                'email' => 'pooja.chhabra67@outlook.com',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Rahul Grover',
                'email' => 'rahul.grover23@yahoo.co.in',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Shilpa Saini',
                'email' => 'shilpa.saini89@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Anil Bisht',
                'email' => 'anil.bisht56@gmail.com',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Reena Chawla',
                'email' => 'reena.chawla32@yahoo.co.in',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Vijay Malhotra',
                'email' => 'vijay.malhotra78@outlook.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Seema Ahuja',
                'email' => 'seema.ahuja45@gmail.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Amit Chhabra',
                'email' => 'amit.chhabra91@yahoo.co.in',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Neha Grover',
                'email' => 'neha.gro-ok',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini67@outlook.com',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Ritu Bisht',
                'email' => 'ritu.bisht23@gmail.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Pradeep Chawla',
                'email' => 'pradeep.chawla89@yahoo.co.in',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Anita Malhotra',
                'email' => 'anita.malhotra56@outlook.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Rakesh Ahuja',
                'email' => 'rakesh.ahuja32@gmail.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Sneha Chhabra',
                'email' => 'sneha.chhabra78@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Vivek Grover',
                'email' => 'vivek.grover45@yahoo.co.in',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Pallavi Saini',
                'email' => 'pallavi.saini91@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Sanjay Bisht',
                'email' => 'sanjay.bisht67@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Kavita Chawla',
                'email' => 'kavita.chawla23@yahoo.co.in',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Deepak Malhotra',
                'email' => 'deepak.malhotra89@outlook.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja56@gmail.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Manish Chhabra',
                'email' => 'manish.chhabra32@outlook.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Pooja Grover',
                'email' => 'pooja.grover78@yahoo.co.in',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Rahul Saini',
                'email' => 'rahul.saini45@gmail.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Shilpa Bisht',
                'email' => 'shilpa.bisht91@outlook.com',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Anil Chawla',
                'email' => 'anil.chawla67@yahoo.co.in',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra23@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Vijay Ahuja',
                'email' => 'vijay.ahuja89@gmail.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Seema Chhabra',
                'email' => 'seema.chhabra56@outlook.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Amit Grover',
                'email' => 'amit.grover32@yahoo.co.in',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Neha Saini',
                'email' => 'neha.saini78@outlook.com',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Suresh Bisht',
                'email' => 'suresh.bisht45@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Ritu Chawla',
                'email' => 'ritu.chawla91@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Pradeep Malhotra',
                'email' => 'pradeep.malhotra67@yahoo.co.in',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Anita Ahuja',
                'email' => 'anita.ahuja23@outlook.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Rakesh Chhabra',
                'email' => 'rakesh.chhabra89@gmail.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Sneha Grover',
                'email' => 'sneha.grover56@outlook.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Vivek Saini',
                'email' => 'vivek.saini32@yahoo.co.in',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Pallavi Bisht',
                'email' => 'pallavi.bisht78@outlook.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Sanjay Chawla',
                'email' => 'sanjay.chawla45@gmail.com',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Kavita Malhotra',
                'email' => 'kavita.malhotra91@outlook.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Deepak Ahuja',
                'email' => 'deepak.ahuja67@yahoo.co.in',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Kriti Chhabra',
                'email' => 'kriti.chhabra23@outlook.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Manish Grover',
                'email' => 'manish.grover89@gmail.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Pooja Saini',
                'email' => 'pooja.saini56@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Rahul Bisht',
                'email' => 'rahul.bisht32@yahoo.co.in',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Shilpa Chawla',
                'email' => 'shilpa.chawla78@outlook.com',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Anil Malhotra',
                'email' => 'anil.malhotra45@gmail.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Reena Ahuja',
                'email' => 'reena.ahuja91@outlook.com',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Vijay Chhabra',
                'email' => 'vijay.chhabra67@yahoo.co.in',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover23@outlook.com',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Amit Saini',
                'email' => 'amit.saini89@gmail.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Neha Bisht',
                'email' => 'neha.bisht56@outlook.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Suresh Chawla',
                'email' => 'suresh.chawla32@yahoo.co.in',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Ritu Malhotra',
                'email' => 'ritu.malhotra78@outlook.com',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Pradeep Ahuja',
                'email' => 'pradeep.ahuja45@gmail.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Anita Chhabra',
                'email' => 'anita.chhabra91@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Rakesh Grover',
                'email' => 'rakesh.grover67@yahoo.co.in',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Sneha Saini',
                'email' => 'sneha.saini23@outlook.com',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Vivek Bisht',
                'email' => 'vivek.bisht89@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Pallavi Chawla',
                'email' => 'pallavi.chawla56@outlook.com',
                'title' => 'Amazing Product',
                'content' => 'This product is truly amazing. It’s sturdy, easy to use, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s made my tasks much easier. The manual could use more details, but that’s a minor issue. Highly recommend this!'
            ],
            [
                'name' => 'Sanjay Malhotra',
                'email' => 'sanjay.malhotra32@yahoo.co.in',
                'title' => 'Great Purchase',
                'content' => 'This product is a great purchase. It’s well-designed, reliable, and very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this buy.'
            ],
            [
                'name' => 'Kavita Ahuja',
                'email' => 'kavita.ahuja78@outlook.com',
                'title' => 'Excellent Quality',
                'content' => 'The quality of this product is excellent. It’s durable, user-friendly, and performs as promised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A more detailed manual would be helpful, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Deepak Chhabra',
                'email' => 'deepak.chhabra45@gmail.com',
                'title' => 'Really Happy',
                'content' => 'I’m really happy with this product. It’s well-crafted, easy to use, and looks very sleek. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. Great purchase overall!'
            ],
            [
                'name' => 'Kriti Grover',
                'email' => 'kriti.grover91@outlook.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic. It’s sturdy, performs reliably, and is very easy to set up. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been flawless. The manual could be a bit clearer, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Manish Saini',
                'email' => 'manish.saini67@yahoo.co.in',
                'title' => 'Worth It',
                'content' => 'This product is definitely worth the price. It’s well-designed, user-friendly, and performs as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a great addition to my routine. A clearer setup guide would be nice, but I’m really happy.'
            ],
            [
                'name' => 'Pooja Bisht',
                'email' => 'pooja.bisht23@outlook.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s durable, easy to operate, and feels very premium. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it regularly, and it’s been very reliable. The only thing I’d suggest is a more detailed manual. Highly recommend this!'
            ],
            [
                'name' => 'Rahul Chawla',
                'email' => 'rahul.chawla89@gmail.com',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-crafted, reliable, and very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s made my tasks much easier. The instructions could be clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Shilpa Malhotra',
                'email' => 'shilpa.malhotra56@outlook.com',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s sturdy, user-friendly, and performs exactly as advertised. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m very satisfied.'
            ],
            [
                'name' => 'Anil Ahuja',
                'email' => 'anil.ahuja32@yahoo.co.in',
                'title' => 'Excellent Buy',
                'content' => 'This product is an excellent buy. It’s durable, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only suggestion is to include a clearer instruction guide. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Reena Chhabra',
                'email' => 'reena.chhabra78@outlook.com',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s well-designed, performs reliably, and is very easy to use. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Vijay Grover',
                'email' => 'vijay.grover45@gmail.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s sturdy, user-friendly, and looks exactly as advertised. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Seema Saini',
                'email' => 'seema.saini91@outlook.com',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-crafted, performs reliably, and is very easy to set up. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Amit Bisht',
                'email' => 'amit.bisht67@yahoo.co.in',
                'title' => 'Super Product',
                'content' => 'This product is super. It’s durable, easy to operate, and feels very premium. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it for a week, and it’s been very reliable. The instructions could be clearer, but that’s a minor issue. I’m very happy with this buy.'
            ],
            [
                'name' => 'Neha Chawla',
                'email' => 'neha.chawla23@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'I’m very satisfied with this product. It’s well-crafted, user-friendly, and performs exactly as expected. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it daily, and it’s been a fantastic addition to my routine. A more comprehensive manual would be nice, but I’m really pleased.'
            ],
            [
                'name' => 'Meghna Chawla',
                'email' => 'meghna.chawla59@gmail.com',
                'title' => 'Really Impressed',
                'content' => 'I’m really impressed with this product. It’s well-crafted, easy to use, and performs flawlessly. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a game-changer. The manual could use more details, but that’s a small issue. Highly satisfied!'
            ],
            [
                'name' => 'Kunal Bedi',
                'email' => 'kunal.bedi86@outlook.com',
                'title' => 'Fantastic Quality',
                'content' => 'The quality of this product is fantastic. It’s durable, user-friendly, and looks exactly as advertised. The delivery was quick, and the packaging was excellent, keeping the product safe. I’ve been using it daily, and it’s been very reliable. A clearer instruction guide would be nice, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Shivani Rawat',
                'email' => 'shivani.rawat43@yahoo.co.in',
                'title' => 'Worth Every Penny',
                'content' => 'This product is worth every penny. It’s well-designed, performs reliably, and is very easy to use. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for a week, and it’s been flawless. The only thing I’d suggest is a more detailed manual. Great purchase!'
            ],
            [
                'name' => 'Aryan Khurana',
                'email' => 'aryan.khurana70@gmail.com',
                'title' => 'Superb Product',
                'content' => 'This product is superb! It’s sturdy, easy to operate, and feels very premium. The delivery was on time, and the packaging was sturdy, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. A slightly faster shipping option would be nice, but I’m extremely satisfied.'
            ],
            [
                'name' => 'Priyanka Datta',
                'email' => 'priyanka.datta17@outlook.com',
                'title' => 'Bahut Mast Hai',
                'content' => 'Yeh product bahut mast hai! Quality ekdum top-notch hai, aur use karna bilkul asaan hai. Delivery jaldi hui, aur packaging bhi badhiya thi. Maine isse har roz use kiya, aur koi issue nahi aaya. Bas thodi si instructions aur clear hoti toh perfect tha. Definitely recommend karungi!'
            ],
            [
                'name' => 'Rajat Khandelwal',
                'email' => 'rajat.khandelwal54@gmail.com',
                'title' => 'Very Happy',
                'content' => 'I’m very happy with this product. It’s well-made, easy to use, and performs exactly as promised. The delivery was quick, and the packaging was excellent, ensuring no damage. I’ve been using it daily, and it’s been very reliable. The manual could be a bit clearer, but that’s a minor issue. Great buy!'
            ],
            [
                'name' => 'Nandini Bose',
                'email' => 'nandini.bose81@yahoo.co.in',
                'title' => 'Great Experience',
                'content' => 'This product has provided a great experience. It’s durable, user-friendly, and looks very sleek. The delivery was on time, and the packaging was secure, keeping the product safe. I’ve been using it regularly, and it’s been flawless. A more detailed setup guide would be helpful, but I’m very satisfied with this purchase.'
            ],
            [
                'name' => 'Vivek Chaturvedi',
                'email' => 'vivek.chaturvedi28@outlook.com',
                'title' => 'Amazing Buy',
                'content' => 'This product is an amazing buy. It’s well-crafted, easy to set up, and performs reliably. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for various tasks, and it’s been fantastic. The only suggestion is to include a clearer manual. Highly recommend this!'
            ],
            [
                'name' => 'Khushi Ahuja',
                'email' => 'khushi.ahuja65@gmail.com',
                'title' => 'Paisa Vasool',
                'content' => 'Yeh product bilkul paisa vasool hai! Quality aur performance dono ekdum badhiya hai. Delivery time pe hui, aur packaging ne product ko safe rakha. Maine isse daily use kiya, aur koi problem nahi aayi. Thodi si manual mein aur details hoti toh aur achha tha. Sabko recommend karungi!'
            ],
            [
                'name' => 'Harsh Vardhan',
                'email' => 'harsh.vardhan92@yahoo.co.in',
                'title' => 'Really Nice',
                'content' => 'This product is really nice. It’s sturdy, easy to use, and performs exactly as expected. The delivery was quick, and the packaging was excellent, ensuring no damage. I’ve been using it regularly, and it’s been a great addition to my routine. The instructions could be clearer, but overall, a solid purchase.'
            ],
            [
                'name' => 'Anushka Puri',
                'email' => 'anushka.puri49@outlook.com',
                'title' => 'Great Quality',
                'content' => 'The quality of this product is outstanding. It’s well-designed, reliable, and very easy to set up. The delivery was on time, and the packaging was sturdy, keeping the product safe. I’ve been using it daily, and it’s been perfect. A more detailed manual would be nice, but I’m very happy with this buy.'
            ],
            [
                'name' => 'Sanket Jindal',
                'email' => 'sanket.jindal76@gmail.com',
                'title' => 'Loved It!',
                'content' => 'I absolutely love this product! It’s user-friendly, durable, and performs flawlessly. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it for a week, and it’s made my tasks so much easier. The only suggestion is to include a clearer instruction guide. Highly recommend!'
            ],
            [
                'name' => 'Trisha Luthra',
                'email' => 'trisha.luthra23@yahoo.co.in',
                'title' => 'Very Impressed',
                'content' => 'I’m very impressed with this product. It’s well-made, easy to use, and performs flawlessly. The delivery was on time, and the packaging was secure, ensuring no damage. I’ve been using it regularly, and it’s been a game-changer. The manual could use more details, but that’s a small issue. Highly satisfied!'
            ],
            [
                'name' => 'Amitabh Shukla',
                'email' => 'amitabh.shukla60@outlook.com',
                'title' => 'Ekdam Badhiya',
                'content' => 'Yeh product ekdum badhiya hai! Quality aur design dono lajawab hai. Delivery jaldi hui, aur packaging ne product ko safe rakha. Maine isse har roz use kiya, aur performance bilkul perfect hai. Thodi si instructions aur clear hoti toh aur achha tha. Sabko bolunga isse try karo!'
            ],
            [
                'name' => 'Shalini Khera',
                'email' => 'shalini.khera87@gmail.com',
                'title' => 'Fantastic Product',
                'content' => 'This product is fantastic! It’s durable, easy to operate, and looks very sleek. The delivery was quick, and the packaging was excellent, ensuring no damage. I’ve been using it daily, and it’s been very reliable. A clearer setup guide would be helpful, but that’s a minor issue. I’m very happy with this purchase.'
            ],
            [
                'name' => 'Ravi Taneja',
                'email' => 'ravi.taneja34@yahoo.co.in',
                'title' => 'Worth It',
                'content' => 'This product is completely worth the price. It’s well-crafted, user-friendly, and performs as promised. The delivery was on time, and the packaging was sturdy, keeping the product safe. I’ve been using it regularly, and it’s been a great addition to my routine. The manual could be a bit clearer, but I’m very satisfied.'
            ],
            [
                'name' => 'Divya Mital',
                'email' => 'divya.mital71@outlook.com',
                'title' => 'Superb Quality',
                'content' => 'The quality of this product is superb. It’s sturdy, performs reliably, and is very easy to use. The delivery was prompt, and the packaging ensured it arrived in perfect condition. I’ve been using it daily, and it’s been fantastic. The only thing I’d suggest is a more detailed manual. Highly recommend this product!'
            ],
            [
                'name' => 'Rahul Sharma',
                'email' => 'rahul.sharma92@gmail.com',
                'title' => 'Really Impressed!',
                'content' => 'This product is amazing and works perfectly for my needs. The packaging was neat, and delivery was on time. I’ve been using it daily, and it’s very reliable. Highly recommend to everyone!'
            ],
            [
                'name' => 'Pooja Verma',
                'email' => 'pooja.v12@yahoo.co.in',
                'title' => 'Bahut Achha Hai',
                'content' => 'Maine yeh product try kiya and bilkul value for money hai. Thodi si instructions clearer ho sakti thi, but overall mast hai!'
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amitpatel.87@gmail.com',
                'title' => 'Worth Every Penny',
                'content' => 'Very happy with this purchase. The quality is top-notch, and it’s super easy to use. Will definitely buy again!'
            ],
            [
                'name' => 'Sneha Gupta',
                'email' => 'sneha.gupta21@outlook.com',
                'title' => 'Good Product',
                'content' => 'The product is really nice and does what it promises. Only thing is delivery took a day extra, but no big issue. I’m satisfied with the performance.'
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh45@gmail.com',
                'title' => 'Badhiya Choice',
                'content' => 'Yeh product ekdum zabardast hai. Easy to set up and use. Bas thodi si packaging improve ho sakti hai. Overall, very happy!'
            ],
            [
                'name' => 'Anjali Nair',
                'email' => 'anjalinair.76@gmail.com',
                'title' => 'Super Satisfied',
                'content' => 'I’m so happy with this product! It’s exactly as described, and the quality is fantastic. The delivery was quick, and customer support was helpful when I had a query. Totally recommend it.'
            ],
            [
                'name' => 'Rakesh Kumar',
                'email' => 'rakesh.kumar90@yahoo.co.in',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth it. Works smoothly, no complaints. Customer service bhi achha tha.'
            ],
            [
                'name' => 'Divya Joshi',
                'email' => 'divya.joshi88@gmail.com',
                'title' => 'Nice Experience',
                'content' => 'The product is great, and I’m using it daily. Just wish the manual was a bit more detailed. Otherwise, all good! Really happy with the purchase.'
            ],
            [
                'name' => 'Suresh Reddy',
                'email' => 'suresh.reddy55@outlook.com',
                'title' => 'Awesome Buy',
                'content' => 'Really impressed with the quality and performance. Delivery was quick, and the product is exactly as shown.'
            ],
            [
                'name' => 'Neha Kapoor',
                'email' => 'neha.kapoor32@gmail.com',
                'title' => 'Ek Number Product',
                'content' => 'Yeh product dil jeet liya! Bahut hi acha hai, and price bhi reasonable. Definitely recommend karungi.'
            ],
            [
                'name' => 'Kiran Desai',
                'email' => 'kiran.desai29@gmail.com',
                'title' => 'Very Happy',
                'content' => 'This is a fantastic product. Easy to use and great quality. The packaging was also very secure. I’ve recommended it to my friends already.'
            ],
            [
                'name' => 'Manish Agrawal',
                'email' => 'manish.agrawal81@yahoo.co.in',
                'title' => 'Good Quality',
                'content' => 'I liked the product a lot. It’s sturdy and works perfectly. Thoda aur fast delivery hota toh aur acha.'
            ],
            [
                'name' => 'Shalini Menon',
                'email' => 'shalini.menon64@gmail.com',
                'title' => 'Loved It',
                'content' => 'Absolutely love this product! It’s so convenient and well-made. Will definitely buy more from this brand.'
            ],
            [
                'name' => 'Arjun Malhotra',
                'email' => 'arjun.malhotra77@gmail.com',
                'title' => 'Zabardast Product',
                'content' => 'Maza aa gaya is product ko use karke. Bilkul as expected tha. Bas thodi si price kam hoti toh perfect tha. Still, very satisfied!'
            ],
            [
                'name' => 'Meena Iyer',
                'email' => 'meena.iyer50@outlook.com',
                'title' => 'Highly Recommend',
                'content' => 'This product is amazing. It’s super easy to use, and the quality is excellent. Very happy with my purchase!'
            ],
            [
                'name' => 'Sanjay Mehra',
                'email' => 'sanjay.mehra33@gmail.com',
                'title' => 'Great Purchase',
                'content' => 'The product is exactly what I needed. It’s well-designed and performs flawlessly. Delivery was on time, and the packaging was secure. Will buy again!'
            ],
            [
                'name' => 'Priya Rao',
                'email' => 'priya.rao89@yahoo.co.in',
                'title' => 'Mast Product Hai',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha, lekin koi badi baat nahi.'
            ],
            [
                'name' => 'Deepak Chauhan',
                'email' => 'deepak.chauhan44@gmail.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Lakshmi Pillai',
                'email' => 'lakshmi.pillai67@outlook.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied with the purchase.'
            ],
            [
                'name' => 'Rohit Bansal',
                'email' => 'rohit.bansal22@gmail.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Kavita Thakur',
                'email' => 'kavita.thakur95@yahoo.co.in',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Naveen Yadav',
                'email' => 'naveen.yadav78@gmail.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Swati Kulkarni',
                'email' => 'swati.kulkarni31@outlook.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive when I reached out.'
            ],
            [
                'name' => 'Ajay Saxena',
                'email' => 'ajay.saxena66@gmail.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product sach mein lajawab hai. Har paisa wasool hai. Thodi si delivery time kam hoti toh aur maza aata. But overall, zabardast!'
            ],
            [
                'name' => 'Rekha Dubey',
                'email' => 'rekha.dubey53@yahoo.co.in',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Vinod Mishra',
                'email' => 'vinod.mishra49@gmail.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Anita Bhatt',
                'email' => 'anita.bhatt84@outlook.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Sunil Chawla',
                'email' => 'sunil.chawla70@gmail.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Geeta Rana',
                'email' => 'geeta.rana27@yahoo.co.in',
                'title' => 'Bahut Achha Product',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Mohan Lal',
                'email' => 'mohan.lal62@gmail.com',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Shruti Trivedi',
                'email' => 'shruti.trivedi19@outlook.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Vivek Arora',
                'email' => 'vivek.arora36@gmail.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata. Still, very happy!'
            ],
            [
                'name' => 'Suman Das',
                'email' => 'suman.das93@yahoo.co.in',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Ritu Sen',
                'email' => 'ritu.sen48@gmail.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Gaurav Khanna',
                'email' => 'gaurav.khanna75@outlook.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Megha Rawat',
                'email' => 'megha.rawat30@gmail.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Prakash Tiwari',
                'email' => 'prakash.tiwari65@yahoo.co.in',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Nidhi Bose',
                'email' => 'nidhi.bose82@gmail.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Alok Jain',
                'email' => 'alok.jain57@outlook.com',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Tara Dutta',
                'email' => 'tara.dutta24@gmail.com',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Siddharth Roy',
                'email' => 'siddharth.roy79@yahoo.co.in',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Asha Negi',
                'email' => 'asha.negi46@gmail.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Kunal Vohra',
                'email' => 'kunal.vohra91@outlook.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Rani Saini',
                'email' => 'rani.saini68@gmail.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Harish Bisht',
                'email' => 'harish.bisht23@yahoo.co.in',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Monika Sethi',
                'email' => 'monika.sethi80@gmail.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Abhishek Goel',
                'email' => 'abhishek.goel35@outlook.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Jyoti Bhardwaj',
                'email' => 'jyoti.bhardwaj52@gmail.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Ravi Shukla',
                'email' => 'ravi.shukla69@yahoo.co.in',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Smita Grover',
                'email' => 'smita.grover26@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Nitin Goyal',
                'email' => 'nitin.goyal83@outlook.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Pallavi Mehra',
                'email' => 'pallavi.mehra40@gmail.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Yogesh Pant',
                'email' => 'yogesh.pant97@yahoo.co.in',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Shivani Tandon',
                'email' => 'shivani.tandon54@gmail.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Mahesh Bhalla',
                'email' => 'mahesh.bhalla71@outlook.com',
                'title' => ' 🙂 Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Komal Ahuja',
                'email' => 'komal.ahuja28@gmail.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Sachin Luthra',
                'email' => 'sachin.luthra85@yahoo.co.in',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Bhavna Makhija',
                'email' => 'bhavna.makhija42@gmail.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Ankur Bhasin',
                'email' => 'ankur.bhasin99@outlook.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Richa Chhabra',
                'email' => 'richa.chhabra56@gmail.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Vishal Nanda',
                'email' => 'vishal.nanda73@yahoo.co.in',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Preeti Malhotra',
                'email' => 'preeti.malhotra30@gmail.com',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Sameer Khosla',
                'email' => 'sameer.khosla87@outlook.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Neelam Grover',
                'email' => 'neelam.grover44@gmail.com',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Rohit Chawla',
                'email' => 'rohit.chawla61@yahoo.co.in',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Sonali Mehra',
                'email' => 'sonali.mehra18@gmail.com',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Mukesh Saini',
                'email' => 'mukesh.saini75@outlook.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Poonam Vohra',
                'email' => 'poonam.vohra32@gmail.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Arvind Bisht',
                'email' => 'arvind.bisht89@yahoo.co.in',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Shweta Sethi',
                'email' => 'shweta.sethi46@gmail.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Nikhil Goel',
                'email' => 'nikhil.goel63@outlook.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Ruchi Bhardwaj',
                'email' => 'ruchi.bhardwaj20@gmail.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Akhil Shukla',
                'email' => 'akhil.shukla77@yahoo.co.in',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Tanya Grover',
                'email' => 'tanya.grover34@gmail.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Vikas Goyal',
                'email' => 'vikas.goyal91@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Anu Mehra',
                'email' => 'anu.mehra48@gmail.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant65@yahoo.co.in',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon22@gmail.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla79@outlook.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja36@gmail.com',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra93@yahoo.co.in',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija50@gmail.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin67@outlook.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra24@gmail.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda81@yahoo.co.in',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra38@gmail.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla95@outlook.com',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover52@gmail.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla69@yahoo.co.in',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra26@gmail.com',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini83@outlook.com',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Ritu Vohra',
                'email' => 'ritu.vohra40@gmail.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht97@yahoo.co.in',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Anita Sethi',
                'email' => 'anita.sethi54@gmail.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Rakesh Goel',
                'email' => 'rakesh.goel71@outlook.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Sneha Bhardwaj',
                'email' => 'sneha.bhardwaj28@gmail.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Vivek Shukla',
                'email' => 'vivek.shukla85@yahoo.co.in',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover42@gmail.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Sanjay Goyal',
                'email' => 'sanjay.goyal99@outlook.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Kavita Mehra',
                'email' => 'kavita.mehra56@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant73@yahoo.co.in',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon30@gmail.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla87@outlook.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja44@gmail.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra61@yahoo.co.in',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija78@gmail.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin95@outlook.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra52@gmail.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda69@yahoo.co.in',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra26@gmail.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla83@outlook.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover40@gmail.com',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla97@yahoo.co.in',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra54@gmail.com',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini71@outlook.com',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Ritu Vohra',
                'email' => 'ritu.vohra28@gmail.com',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht85@yahoo.co.in',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Anita Sethi',
                'email' => 'anita.sethi42@gmail.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Rakesh Goel',
                'email' => 'rakesh.goel99@outlook.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Sneha Bhardwaj',
                'email' => 'sneha.bhardwaj56@gmail.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Vivek Shukla',
                'email' => 'vivek.shukla73@yahoo.co.in',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover30@gmail.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Sanjay Goyal',
                'email' => 'sanjay.goyal87@outlook.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Kavita Mehra',
                'email' => 'kavita.mehra44@gmail.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant61@yahoo.co.in',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon78@gmail.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla95@outlook.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja52@gmail.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra69@yahoo.co.in',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija86@gmail.com',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin23@outlook.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra40@gmail.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda57@yahoo.co.in',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra74@gmail.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla91@outlook.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover28@gmail.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla85@yahoo.co.in',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra42@gmail.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini99@outlook.com',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Ritu Vohra',
                'email' => 'ritu.vohra56@gmail.com',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht73@yahoo.co.in',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Anita Sethi',
                'email' => 'anita.sethi30@gmail.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Rakesh Goel',
                'email' => 'rakesh.goel87@outlook.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Sneha Bhardwaj',
                'email' => 'sneha.bhardwaj44@gmail.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Vivek Shukla',
                'email' => 'vivek.shukla61@yahoo.co.in',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover78@gmail.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Sanjay Goyal',
                'email' => 'sanjay.goyal95@outlook.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Kavita Mehra',
                'email' => 'kavita.mehra52@gmail.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant69@yahoo.co.in',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon86@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla23@outlook.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja40@gmail.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra57@yahoo.co.in',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija74@gmail.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin91@outlook.com',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra28@gmail.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda85@yahoo.co.in',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra42@gmail.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla99@outlook.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover56@gmail.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla73@yahoo.co.in',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra30@gmail.com',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini87@outlook.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Ritu Vohra',
                'email' => 'ritu.vohra44@gmail.com',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht61@yahoo.co.in',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Anita Sethi',
                'email' => 'anita.sethi78@gmail.com',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Rakesh Goel',
                'email' => 'rakesh.goel95@outlook.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Sneha Bhardwaj',
                'email' => 'sneha.bhardwaj52@gmail.com',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Vivek Shukla',
                'email' => 'vivek.shukla69@yahoo.co.in',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover86@gmail.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi sirkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Sanjay Goyal',
                'email' => 'sanjay.goyal23@outlook.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Kavita Mehra',
                'email' => 'kavita.mehra40@gmail.com',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant57@yahoo.co.in',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon74@gmail.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla91@outlook.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja28@gmail.com',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra85@yahoo.co.in',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija42@gmail.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin99@outlook.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra56@gmail.com',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda73@yahoo.co.in',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra30@gmail.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla87@outlook.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover44@gmail.com',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla61@yahoo.co.in',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra78@gmail.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini95@outlook.com',
                'title' => 'Worth It',
                'content' => 'The product is great and worth every penny. It’s easy to use and feels very durable. Very satisfied with my purchase.'
            ],
            [
                'name' => 'Ritu Vohra',
                'email' => 'ritu.vohra52@gmail.com',
                'title' => 'Superb Product',
                'content' => 'Love this product! It’s exactly as described, and the quality is amazing. The delivery was quick, and I’m very happy.'
            ],
            [
                'name' => 'Pradeep Bisht',
                'email' => 'pradeep.bisht69@yahoo.co.in',
                'title' => 'Badhiya Product',
                'content' => 'Yeh product bilkul value for money hai. Quality aur performance dono achhe hai. Bas thodi si packaging improve ho sakti thi.'
            ],
            [
                'name' => 'Anita Sethi',
                'email' => 'anita.sethi86@gmail.com',
                'title' => 'Very Happy',
                'content' => 'This product is fantastic. It’s super easy to use, and the quality is top-notch. I’ve been recommending it to everyone.'
            ],
            [
                'name' => 'Rakesh Goel',
                'email' => 'rakesh.goel23@outlook.com',
                'title' => 'Great Buy',
                'content' => 'Really impressed with this product. It’s well-designed and works perfectly. The delivery was on time, and the packaging was good.'
            ],
            [
                'name' => 'Sneha Bhardwaj',
                'email' => 'sneha.bhardwaj40@gmail.com',
                'title' => 'Mast Product',
                'content' => 'Yeh product ekdum badhiya hai. Price ke hisaab se quality top-notch hai. Thoda sa manual thodi aur clear ho sakta tha.'
            ],
            [
                'name' => 'Vivek Shukla',
                'email' => 'vivek.shukla57@yahoo.co.in',
                'title' => 'Very Nice',
                'content' => 'Really happy with this product. It’s easy to use and feels very durable. Great value for money!'
            ],
            [
                'name' => 'Pallavi Grover',
                'email' => 'pallavi.grover74@gmail.com',
                'title' => 'Fantastic Buy',
                'content' => 'This product exceeded my expectations. It’s super convenient, and the quality is amazing. The delivery was prompt, and I’m very satisfied.'
            ],
            [
                'name' => 'Sanjay Goyal',
                'email' => 'sanjay.goyal91@outlook.com',
                'title' => 'Bahut Badhiya',
                'content' => 'Product bilkul first-class hai. Use karne mein maza aata hai. Bas thodi si packaging better ho sakti thi. Overall, happy hoon!'
            ],
            [
                'name' => 'Kavita Mehra',
                'email' => 'kavita.mehra28@gmail.com',
                'title' => 'Totally Worth It',
                'content' => 'I’m really impressed with this product. It’s very user-friendly and well-made. Highly recommend to others!'
            ],
            [
                'name' => 'Rohit Pant',
                'email' => 'rohit.pant85@yahoo.co.in',
                'title' => 'Good Experience',
                'content' => 'The product is great, and it’s been working perfectly so far. Just took a bit longer to arrive, but the quality makes up for it.'
            ],
            [
                'name' => 'Shalini Tandon',
                'email' => 'shalini.tandon42@gmail.com',
                'title' => 'Super Product',
                'content' => 'Love this product! It’s exactly as advertised, and the quality is top-notch. The customer service was also very responsive.'
            ],
            [
                'name' => 'Deepak Bhalla',
                'email' => 'deepak.bhalla99@outlook.com',
                'title' => 'Ekdam Mast',
                'content' => 'Yeh product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Kriti Ahuja',
                'email' => 'kriti.ahuja56@gmail.com',
                'title' => 'Very Satisfied',
                'content' => 'This product is fantastic. It’s easy to use, and the quality is excellent. I’ve been recommending it to all my friends.'
            ],
            [
                'name' => 'Manish Luthra',
                'email' => 'manish.luthra73@yahoo.co.in',
                'title' => 'Great Quality',
                'content' => 'Really happy with the product. It’s sturdy and works perfectly. The packaging was good, and delivery was on time.'
            ],
            [
                'name' => 'Pooja Makhija',
                'email' => 'pooja.makhija30@gmail.com',
                'title' => 'Loved the Product',
                'content' => 'This product is amazing! It’s so convenient and well-designed. The delivery was quick, and I’m very happy with my purchase.'
            ],
            [
                'name' => 'Rahul Bhasin',
                'email' => 'rahul.bhasin87@outlook.com',
                'title' => 'Value for Money',
                'content' => 'Bought this and it’s totally worth the price. Works smoothly, and the quality is great. Customer service bhi acha tha.'
            ],
            [
                'name' => 'Shilpa Chhabra',
                'email' => 'shilpa.chhabra44@gmail.com',
                'title' => 'Bahut Achha',
                'content' => 'Yeh product bilkul perfect hai mere liye. Quality ekdum badhiya hai. Bas thodi si instructions aur clear honi chahiye thi.'
            ],
            [
                'name' => 'Anil Nanda',
                'email' => 'anil.nanda61@yahoo.co.in',
                'title' => 'Highly Impressed',
                'content' => 'The product is excellent, and I’m very satisfied. It’s easy to use, and the quality is top-notch. Will buy again!'
            ],
            [
                'name' => 'Reena Malhotra',
                'email' => 'reena.malhotra78@gmail.com',
                'title' => 'Awesome Product',
                'content' => 'Really love this product! It’s super convenient and works perfectly. The packaging was great, and delivery was on time. Highly recommend!'
            ],
            [
                'name' => 'Vijay Khosla',
                'email' => 'vijay.khosla95@outlook.com',
                'title' => 'Zabardast Buy',
                'content' => 'Is product ne dil jeet liya. Quality aur performance dono top-class hai. Thodi si price kam hoti toh aur maza aata.'
            ],
            [
                'name' => 'Seema Grover',
                'email' => 'seema.grover52@gmail.com',
                'title' => 'Very Nice Product',
                'content' => 'The product is really good and easy to use. I’m very satisfied with the quality. Just wish the delivery was a bit faster.'
            ],
            [
                'name' => 'Amit Chawla',
                'email' => 'amit.chawla69@yahoo.co.in',
                'title' => 'Totally Satisfied',
                'content' => 'This product is fantastic! It’s well-made and super easy to use. The customer support was also very helpful. Great purchase!'
            ],
            [
                'name' => 'Neha Mehra',
                'email' => 'neha.mehra86@gmail.com',
                'title' => 'Ek Number',
                'content' => 'Yeh product ekdum mast hai. Price ke hisaab se quality bahut achhi hai. Delivery bhi time pe hui. Highly recommend!'
            ],
            [
                'name' => 'Suresh Saini',
                'email' => 'suresh.saini23@outlook.com',
                'title' => 'Great Experience',
                'content' => 'I’m really happy with this product. It’s very user-friendly and the quality is excellent. The packaging was also very secure.'
            ],
        ];
    }
}
