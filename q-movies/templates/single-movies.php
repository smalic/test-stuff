<?php
get_header();
?>

    <div class="q-agency-movie">
        <?php
        if ( have_posts() ) {
            while( have_posts() ) {
                the_post();
                ?>
                <div class="q-agency-movie__title">
                    <?php the_title( '<h1>', '</h1>' ); ?>
                </div>

                <div class="q-agency-movie__content">
                    <?php the_content(); ?>
                </div>
                <?php
            }
        }
        ?>
    </div>

<?php
get_footer();
