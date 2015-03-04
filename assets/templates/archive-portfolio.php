<?php
/**
* Archives
*
* @package    birds-portfolio
* @subpackage birds-portfolio/assets/templates
* @since      1.0.0
*/

get_header(); ?>

<div class="row">

    <section id="primary" class="content-area">

        <div class="large-12 columns">

        <main id="main" class="site-main" role="main">

            <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title">
                    <?php single_term_title(); ?>
                </h1>
            </header><!-- .page-header -->

            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>

               <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php
                            $short_desc = get_post_meta(get_the_ID(), 'birds_portfolio_short_desc', TRUE);
                            $client = get_post_meta(get_the_ID(), 'birds_portfolio_client', TRUE);
                        ?>
                        <div class="archives_first">
                           <?php if (has_post_thumbnail()) { ?>
                           <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"> <?php the_post_thumbnail( 'medium', array('alt' => get_the_title()) ); ?> </a>
                           <?php } else { ?>
                           <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                           <?php } ?>
                        </div>
                        <?php $short_desc = get_post_meta(get_the_ID(), 'birds_portfolio_short_desc', TRUE); ?>
                        <div class="archives_second">
                            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title( '<h2 class="archives_title">', '</h2>' ); ?></a>
                            <?php
                                echo $short_desc;
                            ?>
                        </div>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer">
                        <?php
                            if (function_exists('birds_entry_footer')) {
                                birds_entry_footer();
                            }
                        ?>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-## -->

            <?php endwhile; ?>

            <?php posts_nav_link(); ?>

        <?php else : ?>

            <?php echo _e('No project in this category','birds-portfolio') ?>

        <?php endif; ?>

        </main><!-- #main -->

        </div><!-- .large-12 -->

    </section><!-- #primary -->

</div><!-- .row -->

<?php get_footer(); ?>
