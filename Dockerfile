# Use the official Drupal 11 image as base
FROM drupal:11

# Remove the default Drupal installation in /opt/drupal
RUN rm -rf /opt/drupal/*

# Set the working directory to /opt/drupal
WORKDIR /opt/drupal

# Copy your custom Drupal project files into /opt/drupal
COPY ./ /opt/drupal

# Create a symlink from /opt/drupal/web to /var/www/html
RUN ln -s /opt/drupal/web /var/www/html

# Set appropriate permissions (if necessary)
RUN chown -R www-data:www-data /opt/drupal

