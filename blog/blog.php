<?php

    include 'partials/header.php';

    $query = "SELECT * FROM posts ORDER BY date_time DESC LIMIT 9";
    $posts = mysqli_query($conn, $query);

?>

    <section class="search_bar">
        <form class="container search_bar-container" action="<?= ROOT_URL ?>search.php" method="get">
            <div class="search">
                <div class="icon">
                  <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" id="search-alt"><path fill="#FFFFFF" d="M21.07,16.83,19,14.71a3.08,3.08,0,0,0-3.4-.57l-.9-.9a7,7,0,1,0-1.41,1.41l.89.89A3,3,0,0,0,14.71,19l2.12,2.12a3,3,0,0,0,4.24,0A3,3,0,0,0,21.07,16.83Zm-8.48-4.24a5,5,0,1,1,0-7.08A5,5,0,0,1,12.59,12.59Zm7.07,7.07a1,1,0,0,1-1.42,0l-2.12-2.12a1,1,0,0,1,0-1.42,1,1,0,0,1,1.42,0l2.12,2.12A1,1,0,0,1,19.66,19.66Z"></path></svg>
                </div>
                <input type="search" name="search" id="" placeholder="Search">
            </div>
            <button type="submit" name="submit" class="btn">Go</button>
        </form>
    </section>

    <section class="posts <?= $featured ? '' : 'section_extra-margin' ?>">
        <div class="container posts_container">
            <?php while($post = mysqli_fetch_assoc($posts)) : ?>
                <article class="post">
                    <div class="post_thumbnail">
                        <img src="./images/<?= $post['thumbnail'] ?>" alt="">
                    </div>
                    <div class="post_info">
                        <?php
                            $category_id = $post['category_id'];
                            $category_query = "SELECT * FROM categories WHERE ID=$category_id";
                            $category_result = mysqli_query($conn, $category_query);
                            $category = mysqli_fetch_assoc($category_result);
                        ?>
                        <a href="<?= ROOT_URL ?>category-posts.php?ID=<?= $category['ID'] ?>" class="category_button"><?= $category['title'] ?></a>
                        <h3 class="post_title"><a href="<?= ROOT_URL ?>post.php?ID=<?= $post['ID'] ?>"><?= $post['title'] ?></a></h3>
                        <p class="post_body"><?= substr($post['body'], 0, 150) ?>...</p>
                        <div class="post_author">
                            <?php 
                                $author_id = $post['author_id'];
                                $author_query = "SELECT * FROM users WHERE ID=$author_id";
                                $author_result = mysqli_query($conn, $author_query);
                                $author = mysqli_fetch_assoc($author_result);
                            ?>
                            <div class="post_author-avatar">
                                <img src="./images/<?= $author['avatar'] ?>" alt="">
                            </div>
                            <div class="post_author-info">
                                <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                                <small><?= date("M d, Y - H:i", strtotime($post['date_time'])) ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile ?>
        </div>
    </section>

    <section class="category_buttons">
        <div class="container category_buttons-container">
            <?php
                $all_categories_query = "SELECT * FROM categories";
                $all_categories = mysqli_query($conn, $all_categories_query);
            ?>
            <?php while($category = mysqli_fetch_assoc($all_categories)) : ?>
                <a href="<?= ROOT_URL ?>category-posts.php?ID=<?= $category['ID'] ?>" class="category_button"><?= $category['title'] ?></a>
            <?php endwhile ?>
        </div>
    </section>

<?php

    include 'partials/footer.php';

?>