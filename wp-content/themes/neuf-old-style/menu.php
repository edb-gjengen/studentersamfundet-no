				<nav id="menu" class="grid_6">
					<ul class="aapningstider">
						<li><a href="#">Åpningstider &#9662;</a>
							<ul>
								<li>
									<table>
											
										<tr>
											<td>Man - tirs</td>
											<td style="text-align:right;">13.00 - 01.00</td>
										</tr>
										<tr>
											<td>Onsdag</td>
											<td style="text-align:right;">- 01.30</td>
										</tr>
										<tr>
											<td>Tors - fre</td>
											<td style="text-align:right;">- 03.00</td>
										</tr>
										<tr>
											<td>Lørdag</td>
											<td style="text-align:right;">15.00 - 03.00</td>
										</tr>
										<tr>
											<td>Kjøkkenet</td>
											<td style="text-align:right;">- 19.00</td>
										</tr>
										<tr id="bokkafeen_tider">
											<td><a href="http://studentersamfundet.no/foreninger.php?id=3">BokCaféen</a> <span style="color:#888888; font-style:italic;">i dag</span></td>
											<td style="text-align:right;"><?php

												$tider = array('Stengt',
														'19.00 - 00.00',
														'19.00 - 00.00',
														'19.00 - 00.00',
														'19.00 - 03.00',
														'19.00 - 03.00',
														'20.00 - 03.00');

												$day = date('w');
												echo '<a href="http://studentersamfundet.no/foreninger.php?id=3">';
												echo $tider[$day];
												echo '</a>';
												?>
											</td>
										</tr>
									</table>
								</li>
							</ul>
						</li>
					</ul> <!-- .aapningstider -->

					<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => 'false' ) ); ?>

				</nav> <!-- #menu -->
