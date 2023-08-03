<?php
namespace WPPP\Frontend;

class Component{
    /**
     * Generate the caption for a project.
     *
     * @return string The caption HTML.
     */
    public function caption(){
        $caption = '<h4>' . get_the_title(get_the_ID()) . '</h4>';
        $caption .= '<p>' . get_the_excerpt( get_the_ID() );
        $caption .= '<a class="readmore" href="' . esc_url( get_the_permalink(get_the_ID() ) ) . '">';
        $caption .= __( 'Read more', 'wp-project-portfolio' );
        $caption .= '</a>';
        return $caption;
    }

    /**
     * Generate the content for a project.
     *
     * @param string $shorter_cont The shorter content to display.
     * @return string The content HTML.
     */
    public function content(){
        $remove_cont_tags = wp_strip_all_tags( get_the_excerpt() );
        $shorter_cont = wp_trim_words( $remove_cont_tags, 8, '' );

        $content = '<div class="text">';
        $content .= '<h4 class="project-title">' . get_the_title(get_the_ID()) . '</h4>';
        $content .= '<p>' . esc_html__($shorter_cont) . '</p>';
        $content .= '<span class="open-popup">' . __('View', 'wp-project-portfolio') . '</span>';
        $content .= '</div>';
        return $content;
    }

    /**
     * Generate the thumbnail for a project.
     *
     * @return string The thumbnail HTML.
     */
    public function thumbnail(){
        $thumbnail = '<div class="thumb" style="background-image: url(' . get_the_post_thumbnail_url(get_the_ID(), 'large') . ');"></div>';
        return $thumbnail;
    }

    /**
     * Render the filter dropdown for project categories.
     *
     * @return void
     */
    public function filter() {
        ?>
        <select id="sort-by-category" class="form-select w-25 d-inline" aria-label="<?php _e( 'Default select', 'wp-project-portfolio' ); ?>">
            <option selected value="all"><?php _e( 'All Categories', 'wp-project-portfolio' ); ?></option>

            <?php
            $categories = get_terms(
                array(
                    'taxonomy'   => 'project_cat', // Replace 'project_cat' with your custom taxonomy slug
                    'hide_empty' => true,
                )
            );

            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                foreach ( $categories as $category ) {
                    echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
                }
            }
            ?>
        </select>
        <?php
    }

    /**
     * Render the sorting dropdown for project titles.
     *
     * @return void
     */
    public function sort() {
        ?>
        <select id="sort-by-title" class="form-select w-25 d-inline" aria-label="<?php _e( 'Default select', 'wp-project-portfolio' ); ?>">
            <option selected disabled value="asc"><?php _e( 'Sort by Title', 'wp-project-portfolio' ); ?></option>
            <option value="asc"><?php _e( 'Sort by Title (A-Z)', 'wp-project-portfolio' ); ?></option>
            <option value="desc"><?php _e( 'Sort by Title (Z-A)', 'wp-project-portfolio' ); ?></option>
        </select>
        <?php
    }

    public function loop(){
        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => 15,
        );

        $project = new \WP_Query( $args );

        $unique_categories = array();
        $project_ids = array(); // Store post IDs here
        $category_counts = [];

        if ( $project->have_posts() ) {
            while ( $project->have_posts() ) {
                $project->the_post();
                $terms = get_the_terms( get_the_ID(), 'project_cat' );
               
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $unique_categories[ $term->slug ] = $term->name;

                        if ( isset( $category_counts[ $term->slug ] ) ) {
                            $category_counts[ $term->slug ]++;
                        } else {
                            $category_counts[ $term->slug ] = 1;
                        }
                    }
                }

                // Store post IDs
                $project_ids[] = get_the_ID();
            }
            wp_reset_postdata();

            return [
                'categories' => $unique_categories,
                'ids'        => $project_ids,
                'cat_counts' => $category_counts
            ];
        }
    }
}
