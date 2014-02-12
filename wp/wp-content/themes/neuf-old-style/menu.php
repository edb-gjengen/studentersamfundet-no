				<nav id="menu" class="grid_6">
					<ul class="aapningstider">
						<li><a href="/aapningstider/">Åpningstider &#9662;</a>
							<ul>
								<li>
									<table>
										<tbody>
											<tr>
												<td>Man - ons</td>
												<td class="right">14.00 - 00.00</td>
											</tr>
											<tr>
												<td>Torsdag</td>
												<td class="right">14.00 - 00.00</td>
											</tr>
											<tr>
												<td>Fredag</td>
												<td class="right">14.00 - 01.00</td>
											</tr>
											<tr>
												<td>Lørdag</td>
												<td class="right">15.00 - 00.00</td>
											</tr>
										</tbody>
									</table>
								</li>
							</ul>
						</li>
					</ul> <!-- .aapningstider -->

					<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => 'false' ) ); ?>

				</nav> <!-- #menu -->

				<nav id="secondary-menu"> 

					<?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => 'false' ) ); ?>

				</nav> <!-- #secondary-menu -->
