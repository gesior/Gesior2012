<?php
if(!defined('INITIALIZED'))
	exit;

if($action == "") {
$main_content .= '

This is a list of the experience points that are required to advance to the various levels. 
Remember you can also check the respective skill bar in your skill window of the client to check your progress towards 
the next level.<BR><BR>
<TABLE BGCOLOR='.$config['site']['darkborder'].' BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%>
<TR BGCOLOR='.$config['site']['vdarkborder'].'><TD CLASS=white COLSPAN=5><B>Experience Table</B></TD></TR>
<TR><TD><TABLE BORDER=0 CELLPADDING=2 CELLSPACING=1 WIDTH=100%>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD><B>Level</B></TD><TD><B>Experience</B></TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>1</TD><TD>0</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>2</TD><TD>100</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>3</TD><TD>200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>4</TD><TD>400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>5</TD><TD>800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>6</TD><TD>1500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>7</TD><TD>2600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>8</TD><TD>4200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>9</TD><TD>6400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>10</TD><TD>9300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>11</TD><TD>13000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>12</TD><TD>17600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>13</TD><TD>23200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>14</TD><TD>29900</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>15</TD><TD>37800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>16</TD><TD>47000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>17</TD><TD>57600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>18</TD><TD>69700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>19</TD><TD>83400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>20</TD><TD>98800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>21</TD><TD>116000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>22</TD><TD>135100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>23</TD><TD>156200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>24</TD><TD>179400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>25</TD><TD>204800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>26</TD><TD>232500</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>27</TD><TD>262600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>28</TD><TD>295200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>29</TD><TD>330400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>30</TD><TD>368300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>31</TD><TD>409000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>32</TD><TD>452600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>33</TD><TD>499200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>34</TD><TD>548900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>35</TD><TD>601800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>36</TD><TD>658000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>37</TD><TD>717600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>38</TD><TD>780700</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>39</TD><TD>847400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>40</TD><TD>917800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>41</TD><TD>992000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>42</TD><TD>1070100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>43</TD><TD>1152200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>44</TD><TD>1238400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>45</TD><TD>1328800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>46</TD><TD>1423500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>47</TD><TD>1522600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>48</TD><TD>1626200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>49</TD><TD>1734400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>50</TD><TD>1847300</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>51</TD><TD>1965000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>52</TD><TD>2087600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>53</TD><TD>2215200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>54</TD><TD>2347900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>55</TD><TD>2485800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>56</TD><TD>2629000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>57</TD><TD>2777600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>58</TD><TD>2931700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>59</TD><TD>3091400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>60</TD><TD>3256800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>61</TD><TD>3428000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>62</TD><TD>3605100</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>63</TD><TD>3788200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>64</TD><TD>3977400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>65</TD><TD>4172800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>66</TD><TD>4374500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>67</TD><TD>4582600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>68</TD><TD>4797200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>69</TD><TD>5018400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>70</TD><TD>5246300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>71</TD><TD>5481000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>72</TD><TD>5722600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>73</TD><TD>5971200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>74</TD><TD>6226900</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>75</TD><TD>6489800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>76</TD><TD>6760000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>77</TD><TD>7037600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>78</TD><TD>7322700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>79</TD><TD>7615400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>80</TD><TD>7915800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>81</TD><TD>8224000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>82</TD><TD>8540100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>83</TD><TD>8864200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>84</TD><TD>9196400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>85</TD><TD>9536800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>86</TD><TD>9885500</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>87</TD><TD>10242600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>88</TD><TD>10608200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>89</TD><TD>10982400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>90</TD><TD>11365300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>91</TD><TD>11757000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>92</TD><TD>12157600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>93</TD><TD>12567200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>94</TD><TD>12985900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>95</TD><TD>13413800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>96</TD><TD>13851000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>97</TD><TD>14297600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>98</TD><TD>14753700</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>99</TD><TD>15219400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>100</TD><TD>15694800</TD></TR>
</TABLE></TD><TD><TABLE BORDER=0 CELLPADDING=2 CELLSPACING=1 WIDTH=100%>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD><B>Level</B></TD><TD><B>Experience</B></TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>101</TD><TD>16180000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>102</TD><TD>16675100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>103</TD><TD>17180200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>104</TD><TD>17695400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>105</TD><TD>18220800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>106</TD><TD>18756500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>107</TD><TD>19302600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>108</TD><TD>19859200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>109</TD><TD>20426400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>110</TD><TD>21004300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>111</TD><TD>21593000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>112</TD><TD>22192600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>113</TD><TD>22803200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>114</TD><TD>23424900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>115</TD><TD>24057800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>116</TD><TD>24702000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>117</TD><TD>25357600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>118</TD><TD>26024700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>119</TD><TD>26703400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>120</TD><TD>27393800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>121</TD><TD>28096000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>122</TD><TD>28810100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>123</TD><TD>29536200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>124</TD><TD>30274400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>125</TD><TD>31024800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>126</TD><TD>31787500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>127</TD><TD>32562600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>128</TD><TD>33350200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>129</TD><TD>34150400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>130</TD><TD>34963300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>131</TD><TD>35789000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>132</TD><TD>36627600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>133</TD><TD>37479200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>134</TD><TD>38343900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>135</TD><TD>39221800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>136</TD><TD>40113000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>137</TD><TD>41017600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>138</TD><TD>41935700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>139</TD><TD>42867400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>140</TD><TD>43812800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>141</TD><TD>44772000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>142</TD><TD>45745100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>143</TD><TD>46732200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>144</TD><TD>47733400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>145</TD><TD>48748800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>146</TD><TD>49778500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>147</TD><TD>50822600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>148</TD><TD>51881200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>149</TD><TD>52954400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>150</TD><TD>54042300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>151</TD><TD>55145000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>152</TD><TD>56262600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>153</TD><TD>57395200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>154</TD><TD>58542900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>155</TD><TD>59705800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>156</TD><TD>60884000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>157</TD><TD>62077600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>158</TD><TD>63286700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>159</TD><TD>64511400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>160</TD><TD>65751800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>161</TD><TD>67008000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>162</TD><TD>68280100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>163</TD><TD>69568200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>164</TD><TD>70872400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>165</TD><TD>72192800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>166</TD><TD>73529500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>167</TD><TD>74882600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>168</TD><TD>76252200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>169</TD><TD>77638400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>170</TD><TD>79041300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>171</TD><TD>80461000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>172</TD><TD>81897600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>173</TD><TD>83351200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>174</TD><TD>84821900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>175</TD><TD>86309800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>176</TD><TD>87815000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>177</TD><TD>89337600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>178</TD><TD>90877700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>179</TD><TD>92435400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>180</TD><TD>94010800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>181</TD><TD>95604000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>182</TD><TD>97215100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>183</TD><TD>98844200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>184</TD><TD>100491400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>185</TD><TD>102156800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>186</TD><TD>103840500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>187</TD><TD>105542600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>188</TD><TD>107263200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>189</TD><TD>109002400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>190</TD><TD>110760300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>191</TD><TD>112537000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>192</TD><TD>114332600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>193</TD><TD>116147200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>194</TD><TD>117980900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>195</TD><TD>119833800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>196</TD><TD>121706000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>197</TD><TD>123597600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>198</TD><TD>125508700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>199</TD><TD>127439400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>200</TD><TD>129389800</TD></TR>
</TABLE></TD><TD><TABLE BORDER=0 CELLPADDING=2 CELLSPACING=1 WIDTH=100%>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD><B>Level</B></TD><TD><B>Experience</B></TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>201</TD><TD>131360000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>202</TD><TD>133350100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>203</TD><TD>135360200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>204</TD><TD>137390400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>205</TD><TD>139440800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>206</TD><TD>141511500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>207</TD><TD>143602600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>208</TD><TD>145714200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>209</TD><TD>147846400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>210</TD><TD>149999300</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>211</TD><TD>152173000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>212</TD><TD>154367600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>213</TD><TD>156583200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>214</TD><TD>158819900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>215</TD><TD>161077800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>216</TD><TD>163357000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>217</TD><TD>165657600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>218</TD><TD>167979700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>219</TD><TD>170323400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>220</TD><TD>172688800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>221</TD><TD>175076000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>222</TD><TD>177485100</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>223</TD><TD>179916200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>224</TD><TD>182369400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>225</TD><TD>184844800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>226</TD><TD>187342500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>227</TD><TD>189862600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>228</TD><TD>192405200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>229</TD><TD>194970400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>230</TD><TD>197558300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>231</TD><TD>200169000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>232</TD><TD>202802600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>233</TD><TD>205459200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>234</TD><TD>208138900</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>235</TD><TD>210841800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>236</TD><TD>213568000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>237</TD><TD>216317600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>238</TD><TD>219090700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>239</TD><TD>221887400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>240</TD><TD>224707800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>241</TD><TD>227552000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>242</TD><TD>230420100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>243</TD><TD>233312200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>244</TD><TD>236228400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>245</TD><TD>239168800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>246</TD><TD>242133500</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>247</TD><TD>245122600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>248</TD><TD>248136200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>249</TD><TD>251174400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>250</TD><TD>254237300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>251</TD><TD>257325000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>252</TD><TD>260437600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>253</TD><TD>263575200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>254</TD><TD>266737900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>255</TD><TD>269925800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>256</TD><TD>273139000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>257</TD><TD>276377600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>258</TD><TD>279641700</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>259</TD><TD>282931400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>260</TD><TD>286246800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>261</TD><TD>289588000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>262</TD><TD>292955100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>263</TD><TD>296348200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>264</TD><TD>299767400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>265</TD><TD>303212800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>266</TD><TD>306684500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>267</TD><TD>310182600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>268</TD><TD>313707200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>269</TD><TD>317258400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>270</TD><TD>320836300</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>271</TD><TD>324441000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>272</TD><TD>328072600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>273</TD><TD>331731200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>274</TD><TD>335416900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>275</TD><TD>339129800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>276</TD><TD>342870000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>277</TD><TD>346637600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>278</TD><TD>350432700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>279</TD><TD>354255400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>280</TD><TD>358105800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>281</TD><TD>361984000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>282</TD><TD>365890100</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>283</TD><TD>369824200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>284</TD><TD>373786400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>285</TD><TD>377776800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>286</TD><TD>381795500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>287</TD><TD>385842600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>288</TD><TD>389918200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>289</TD><TD>394022400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>290</TD><TD>398155300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>291</TD><TD>402317000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>292</TD><TD>406507600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>293</TD><TD>410727200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>294</TD><TD>414975900</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>295</TD><TD>419253800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>296</TD><TD>423561000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>297</TD><TD>427897600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>298</TD><TD>432263700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>299</TD><TD>436659400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>300</TD><TD>441084800</TD></TR>

</TABLE></TD><TD><TABLE BORDER=0 CELLPADDING=2 CELLSPACING=1 WIDTH=100%>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD><B>Level</B></TD><TD><B>Experience</B></TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>301</TD><TD>445540000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>302</TD><TD>450025100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>303</TD><TD>454540200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>304</TD><TD>459085400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>305</TD><TD>463660800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>306</TD><TD>468266500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>307</TD><TD>472902600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>308</TD><TD>477569200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>309</TD><TD>482266400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>310</TD><TD>486994300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>311</TD><TD>491753000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>312</TD><TD>496542600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>313</TD><TD>501363200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>314</TD><TD>506214900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>315</TD><TD>511097800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>316</TD><TD>516012000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>317</TD><TD>520957600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>318</TD><TD>525934700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>319</TD><TD>530943400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>320</TD><TD>535983800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>321</TD><TD>541056000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>322</TD><TD>546160100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>323</TD><TD>551296200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>324</TD><TD>556464400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>325</TD><TD>561664800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>326</TD><TD>566897500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>327</TD><TD>572162600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>328</TD><TD>577460200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>329</TD><TD>582790400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>330</TD><TD>588153300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>331</TD><TD>593549000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>332</TD><TD>598977600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>333</TD><TD>604439200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>334</TD><TD>609933900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>335</TD><TD>615461800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>336</TD><TD>621023000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>337</TD><TD>626617600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>338</TD><TD>632245700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>339</TD><TD>637907400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>340</TD><TD>643602800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>341</TD><TD>649332000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>342</TD><TD>655095100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>343</TD><TD>660892200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>344</TD><TD>666723400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>345</TD><TD>672588800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>346</TD><TD>678488500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>347</TD><TD>684422600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>348</TD><TD>690391200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>349</TD><TD>696394400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>350</TD><TD>702432300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>351</TD><TD>708505000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>352</TD><TD>714612600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>353</TD><TD>720755200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>354</TD><TD>726932900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>355</TD><TD>733145800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>356</TD><TD>739394000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>357</TD><TD>745677600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>358</TD><TD>751996700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>359</TD><TD>758351400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>360</TD><TD>764741800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>361</TD><TD>771168000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>362</TD><TD>777630100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>363</TD><TD>784128200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>364</TD><TD>790662400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>365</TD><TD>797232800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>366</TD><TD>803839500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>367</TD><TD>810482600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>368</TD><TD>817162200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>369</TD><TD>823878400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>370</TD><TD>830631300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>371</TD><TD>837421000</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>372</TD><TD>844247600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>373</TD><TD>851111200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>374</TD><TD>858011900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>375</TD><TD>864949800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>376</TD><TD>871925000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>377</TD><TD>878937600</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>378</TD><TD>885987700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>379</TD><TD>893075400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>380</TD><TD>900200800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>381</TD><TD>907364000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>382</TD><TD>914565100</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>383</TD><TD>921804200</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>384</TD><TD>929081400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>385</TD><TD>936396800</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>386</TD><TD>943750500</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>387</TD><TD>951142600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>388</TD><TD>958573200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>389</TD><TD>966042400</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>390</TD><TD>973550300</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>391</TD><TD>981097000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>392</TD><TD>988682600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>393</TD><TD>996307200</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>394</TD><TD>1003970900</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>395</TD><TD>1011673800</TD></TR>

<TR BGCOLOR='.$config['site']['lightborder'].'><TD>396</TD><TD>1019416000</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>397</TD><TD>1027197600</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>398</TD><TD>1035018700</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>399</TD><TD>1042879400</TD></TR>
<TR BGCOLOR='.$config['site']['lightborder'].'><TD>400</TD><TD>1050779800</TD></TR>
</TABLE></TD></TR>
</TABLE>

</TD>
<TD></TD>
</TR></TABLE>
';
}